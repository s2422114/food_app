<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ようこそ</title>
</head>
<body>
    <h1>ようこそ, <?= htmlspecialchars($_SESSION['username']) ?> さん!</h1>
    <p><a href="logout.php">ログアウト</a></p>
</body>
</html>
