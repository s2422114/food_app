<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = 'mysql:host=localhost;dbname=xampp_login_demo;charset=utf8';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->execute([$_POST['username'], $passwordHash]);

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error = "そのユーザー名は既に使用されています。";
        } else {
            die("データベースエラー: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
</head>
<body>
    <h1>新規登録</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        ユーザー名: <input type="text" name="username" required><br>
        パスワード: <input type="password" name="password" required><br>
        <button type="submit">登録</button>
    </form>
    <p><a href="index.php">ログインはこちら</a></p>
</body>
</html>
