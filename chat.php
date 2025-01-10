<?php
// セッションを開始します。
session_start();

// ユーザー名をセッションから取得
$username = $_SESSION['username'] ?? 'ゲスト';

// 提案する食事オプションを定義
$food_options = [
  'ガッツリ' => ['ジャンクフード', 'ラーメン', '焼肉'],
  'そこそこ' => ['カフェ', '和食'],
  '軽め' => ['スイーツ', 'バー']
];

// POSTリクエストを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];
    $selected_food = $food_options[$type][array_rand($food_options[$type])]; // ランダム選択
    $_SESSION['selected_food'] = $selected_food; // セッションに保存
    header('Location: hotpepper.php'); // hotpepper.phpにリダイレクト
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>チャットボット</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <button class="logout" onclick="location.href='logout.php'">ログアウト</button>
    <div class="container">
        <div class="chat-box">
            <div id="chat-output" class="chat-output"></div>
            <div class="buttons">
                <form method="POST">
                    <button type="submit" name="type" value="ガッツリ" class="btn-gutsuri">ガッツリ</button>
                    <button type="submit" name="type" value="そこそこ" class="btn-sokosoko">そこそこ</button>
                    <button type="submit" name="type" value="軽め" class="btn-karume">軽め</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
