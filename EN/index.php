<?php
ini_set('log_errors', 'Off');

include 'config.php';
function checkTelegramMembership($chat_id)
{
    $botUrl = "https://tl.tahasite.ir/Examiner/index.php";
    $postData = json_encode(['chat_id' => $chat_id]);
    $ch = curl_init($botUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
function oldnameuser($conn)
{
    $sql = "SELECT telegram_id FROM telegram LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['telegram_id'];
    } else {
        return "Telegram ID not found.";
    }
}
$conn = getDbConnection();
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$sql = "SELECT telegram_id FROM telegram WHERE id = 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $chat_id = $row['telegram_id'];
    $response = checkTelegramMembership($chat_id);
    if ($response['membership'] == 'member') {
        $short_code = '';
        $redirect_link = '';
        $short_code_method = '';
        $link_count = 0;
        $redirect_links = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['original_links'])) {
            $original_links = trim($_POST['original_links']);
            $links = explode("\n", $original_links);
            $short_code_method = $_POST['short_code_method'];
            if ($short_code_method == 'fixed') {
                $new_domain = "https://" . $_SERVER['HTTP_HOST'];
                foreach ($links as $index => $original_link) {
                    $original_link = trim($original_link);
                    if ($original_link == '') {
                        continue;
                    }
                    $decoded_link = urldecode($original_link);
                    if ($short_code_method == 'fixed') {
                        $parsed_url = parse_url($decoded_link);
                        $path = $parsed_url['path'];
                        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
                        $short_code = $path . $query;
                    }
                    $sql_check = "SELECT short_code FROM links WHERE short_code='$short_code'";
                    $result_check = $conn->query($sql_check);
                    if ($result_check->num_rows > 0) {
                        echo json_encode(['error' => "The entered short code already exists. Please enter a different code."]);
                        exit;
                    } else {
                        $stmt = $conn->prepare("INSERT INTO links (original_link, short_code) VALUES (?, ?)");
                        $stmt->bind_param("ss", $decoded_link, $short_code);
                        $stmt->execute();
                        $stmt->close();
                        $redirect_links[] = $new_domain . $short_code;
                    }
                }
                $link_count = count($redirect_links);
                echo json_encode(['redirect_links' => $redirect_links, 'link_count' => $link_count]);
                exit;
            }
            if ($short_code_method == 'manual') {
                $manual_short_codes = trim($_POST['manual_short_codes']);
                $manual_codes = explode("\n", $manual_short_codes);
                if (count($links) !== count($manual_codes)) {
                    echo json_encode(['error' => 'The number of links and short codes must be equal.']);
                    exit;
                }
            }
            foreach ($links as $index => $original_link) {
                $original_link = trim($original_link);
                if ($original_link == '') {
                    continue;
                }
                if ($short_code_method == 'manual') {
                    if (isset($manual_codes[$index])) {
                        $short_code = trim($manual_codes[$index]);
                    } else {
                        echo json_encode(['error' => "Please enter a short code for link number " . ($index + 1) . "."]);
                        exit;
                    }
                } elseif ($short_code_method == 'auto') {
                    $short_code = substr(md5(uniqid(rand(), true)), 0, 8);
                } elseif ($short_code_method == 'fixed') {
                    $parsed_url = parse_url($original_link);
                    $path = $parsed_url['path'];
                    $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
                    $short_code = $new_domain . $path . $query;
                }
                $sql_check = "SELECT short_code FROM links WHERE short_code='$short_code'";
                $result_check = $conn->query($sql_check);
                if ($result_check->num_rows > 0) {
                    echo json_encode(['error' => "The entered short code already exists. Please enter a different code."]);
                    exit;
                } else {
                    $stmt = $conn->prepare("INSERT INTO links (original_link, short_code) VALUES (?, ?)");
                    $stmt->bind_param("ss", $original_link, $short_code);
                    $stmt->execute();
                    $stmt->close();
                    $domain = "https://" . $_SERVER['HTTP_HOST'];
                    if ($short_code_method == 'fixed') {
                        $redirect_links[] = $short_code;
                    } else {
                        $redirect_links[] = $domain . "/" . $short_code;
                    }
                }
            }
            $link_count = count($redirect_links);
            echo json_encode(['redirect_links' => $redirect_links, 'link_count' => $link_count]);
            exit;
        }
        include 'inc/main.php';
    } elseif ($response['membership'] == 'not_member') {
        include 'inc/telegram_update.php';
    } else {
        include 'inc/telegram_update.php';
    }
} else {
    echo "No data available.";
}
