<?php
include '../config.php';
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['logged_in'] = true;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            $error = "Incorrect username or password.";
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['short_code']) && isset($_POST['original_link'])) {
    $id = $_POST['id'];
    $short_code = $_POST['short_code'];
    $original_link = $_POST['original_link'];
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE links SET short_code = ?, original_link = ? WHERE id = ?");
    $stmt->bind_param("ssi", $short_code, $original_link, $id);
    if ($stmt->execute()) {
        $message = "Edit successful.";
    } else {
        $message = "Error editing the link.";
    }
    $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM links WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Link deleted successfully.";
    } else {
        $message = "Error deleting the link.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show Links</title>
    <link rel="stylesheet" href="../css/show_links.css">
    <link rel="stylesheet" href="../font/font.css">
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label>Username:</label>
                <input type="text" name="username" required><br>
                <label>Password:</label>
                <input type="password" name="password" required><br>
                <button type="submit">Login</button>
            </form>
        <?php else: ?>
            <h2>List of Shortened Links</h2>
            <?php if (isset($message)): ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php
            $conn = getDbConnection();
            $sql = "SELECT * FROM links";
            $result = $conn->query($sql);
            ?>
            <?php if ($result->num_rows > 0): ?>
                <table border="1" style="width: 100%; text-align: center;">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Short Code</th>
                            <th>Original Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="text" name="short_code" value="<?php echo htmlspecialchars($row['short_code']); ?>" required>
                                </td>
                                <td>
                                    <input type="url" name="original_link" value="<?php echo htmlspecialchars($row['original_link']); ?>" required>
                                </td>
                                <td>
                                    <button type="submit">Edit</button>
                                    </form>
                                    <a href="https://<?php echo $_SERVER['HTTP_HOST'] . '/' . $row['short_code']; ?>" target="_blank">
                                        <button>View</button>
                                    </a>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this link?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            $counter++;
                        endwhile;
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No links to display.</p>
            <?php endif; ?>
            <a href="logout.php">
                <div class="out_style_but">Logout</div>
            </a>
        <?php endif; ?>
    </div>
</body>
</html>
