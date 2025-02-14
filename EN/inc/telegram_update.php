<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_telegram_id'])) {
    $new_telegram_id = $_POST['new_telegram_id'];
    $sql_update = "UPDATE telegram SET telegram_id = ? WHERE id = 1";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $new_telegram_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
$telegram_id = oldnameuser($conn);
echo '<div class="box_log_notm">
<div class="box_imagePtl">
<img class="not_imagePtl" src="./assets/prohibition.png" alt="not">
</div>
    <h2>The entered Telegram ID ' . $telegram_id . ' is not a member of our channel!</h2>
    <p>To use this script for free, you must join our Telegram channel!</p>';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_telegram_id'])) {
    echo "<p class='success-message'>Telegram ID has been successfully updated!</p>";
}
echo '<link rel="stylesheet" href="./css/telegram_update.css"><link rel="stylesheet" href="./font/font.css">';
echo '
<form method="POST" action="">
    <div class="coolinput">
        <label for="new_telegram_id" class="text">New Telegram ID:</label>
        <input name="new_telegram_id" id="new_telegram_id" type="number" placeholder="Example: 123574687" name="input" class="input">
    </div>
    <button type="submit">Change Telegram ID</button>
</form>
<div class="box_chanel">
    <a href="https://t.me/tahasite_chanel" target="_blank"><button class="button_tl" role="button">Telegram Channel</button></a>
    <a href="https://t.me/chatIDrobot" target="_blank"><button class="button_tl" role="button">Get Numeric ID</button></a>
</div>
<div id="id_change">
</div>';
echo '</div>';
?>
