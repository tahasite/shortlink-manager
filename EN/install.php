<?php
ini_set('error_log', NULL);

session_start();
if (file_exists('config.php')) {
    include('config.php');
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
if (!file_exists('config.php')) {
    $_SESSION['install_authenticated'] = true;
}
if (file_exists('config.php') && !isset($_SESSION['install_authenticated'])) {
    if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
        echo "<script>
            if (confirm('Installation has already been completed. Are you sure you want to reinstall?')) {
                window.location.href = 'install.php?confirm=yes'; // Redirect to installation page
            } else {
                window.location.href = 'index.php'; // Redirect to main page
            }
        </script>";
        exit;
    }
}
if (!isset($_SESSION['install_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['install_authenticated'] = true;
                header('Location: install.php');
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            $error = "No user found with this username.";
        }
        $stmt->close();
        $conn->close();
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Login to Installation Page</title>
        <link rel="stylesheet" href="./css/install.css">
        <link rel="stylesheet" href="./font/font.css">
    </head>

    <body>
        <div class="container">
            <h2>Login to Installation Page</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <label>Username:</label>
                <input type="text" name="username" required><br>
                <label>Password:</label>
                <input type="password" name="password" required><br>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>

    </html>
<?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];
    $admin_user = $_POST['admin_user'];
    $admin_pass = $_POST['admin_pass'];
    $telegram_id = $_POST['telegram_id'];

    $conn = new mysqli($db_host, $db_user, $db_pass);
    if ($conn->connect_error) {
        die("Database connection failed.");
    }

    $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($db_name);
    $conn->set_charset('utf8mb4');

    $install_folder = basename(__DIR__);
    $root_path = dirname(__DIR__);
    $htaccess_path = $root_path . '/.htaccess';
    $htaccess_content = "\nRewriteEngine On\n";
    $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
    $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
    $htaccess_content .= "RewriteRule ^([^/]+(?:/[^/]+)*)$ $install_folder/redirect.php?code=$1 [L]\n";
    $htaccess_content .= "RewriteRule ^([a-zA-Z0-9]+(?:/[a-zA-Z0-9]+)*)$ $install_folder/redirect.php?code=$1 [L]\n";

    if (!file_exists($htaccess_path) || strpos(file_get_contents($htaccess_path), "redirect.php") === false) {
        file_put_contents($htaccess_path, $htaccess_content, FILE_APPEND);
    }

    $conn->query("DROP TABLE IF EXISTS admins, telegram, links");

    $sql_links = "CREATE TABLE IF NOT EXISTS links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        original_link TEXT NOT NULL,
        short_code TEXT NOT NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

    $sql_admins = "CREATE TABLE admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

    $sql_telegram = "CREATE TABLE telegram (
        id INT AUTO_INCREMENT PRIMARY KEY,
        telegram_id BIGINT NOT NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

    if ($conn->query($sql_links) && $conn->query($sql_admins) && $conn->query($sql_telegram)) {
        file_put_contents('config.php', "<?php\ndefine('DB_SERVER', '$db_host');\ndefine('DB_USERNAME', '$db_user');\ndefine('DB_PASSWORD', '$db_pass');\ndefine('DB_NAME', '$db_name');\n\nfunction getDbConnection() {\n    \$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);\n    if (\$conn->connect_error) {\n        die('Database connection failed: ' . \$conn->connect_error);\n    }\n    \$conn->set_charset('utf8mb4');\n    return \$conn;\n}\n");

        $password_hash = password_hash($admin_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $admin_user, $password_hash);
        $stmt->execute();
        $stmt->close();

        $stmt_telegram = $conn->prepare("INSERT INTO telegram (telegram_id) VALUES (?)");
        $stmt_telegram->bind_param("i", $telegram_id);
        $stmt_telegram->execute();
        $stmt_telegram->close();

        echo "Installation completed successfully!";
        header('Location: index.php');
        exit;
    } else {
        die("Table creation failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Installation Page</title>
    <link rel="stylesheet" href="./css/install.css">
    <link rel="stylesheet" href="./font/font.css">
</head>

<body>
    <div class="container">
        <h2>Installation Page</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Database Host:</label>
            <input type="text" name="db_host" value="localhost" required><br>
            <label>Database Username:</label>
            <input type="text" name="db_user" required><br>
            <label>Database Password:</label>
            <input type="password" name="db_pass"><br>
            <label>Database Name:</label>
            <input type="text" name="db_name" required><br>
            <label>Admin Username:</label>
            <input type="text" name="admin_user" required><br>
            <label>Admin Password:</label>
            <input type="password" name="admin_pass" required><br>
            <label>Telegram Numeric ID:</label>
            <input type="number" name="telegram_id" required><br>
            <div class="style_number">
                <p>To access the script for free, please enter your Telegram numeric ID and join our channel using the same account.</p>
                <a href="https://t.me/chatIDrobot" target="_blank">Click here to get your numeric ID</a>
                <a href="https://t.me/tahasite_chanel" target="_blank">Join our Telegram channel</a>
            </div>
            <br>
            <button type="submit">Install</button>
        </form>
    </div>
</body>

</html>
