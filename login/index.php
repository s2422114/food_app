<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = 'mysql:host=localhost;dbname=xampp_login_demo;charset=utf8';
    $username = 'root'; // デフォルトのXAMPPユーザー
    $password = '';     // デフォルトのXAMPPパスワードは空

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: welcome.php");
            exit;
        } else {
            $error = "ユーザー名またはパスワードが間違っています。";
        }
    } catch (PDOException $e) {
        die("データベース接続エラー: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        ユーザー名: <input type="text" name="username" required><br>
        パスワード: <input type="password" name="password" required><br>
        <button type="submit">ログイン</button>
    </form>
    <p><a href="register.php">新規登録はこちら</a></p>
</body>
</html>
