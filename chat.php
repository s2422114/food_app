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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャットボット</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="logout">
        <form action="logout.php" method="POST">
            <button type="submit">ログアウト</button>
        </form>
    </div>
    
    <div id="chat-container">
        <div id="chat-box"></div>
        <div id="options">
            <button class="option red" data-value="ガッツリ">ガッツリ</button>
            <button class="option green" data-value="そこそこ">そこそこ</button>
            <button class="option blue" data-value="軽め">軽め</button>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $user_input = $_POST['input'];

    $suggestions = [
        'ガッツリ' => ['ジャンクフード', 'ラーメン', 'レストラン', '洋食', 'カレー', 'アジア・エスニック', '焼肉'],
        'そこそこ' => ['カフェ・喫茶店', '和食'],
        '軽め' => ['居酒屋', 'スイーツ', 'バー', '露店']
    ];

    $selectedSuggestions = $suggestions[$user_input] ?? [];
    $suggestion = $selectedSuggestions[array_rand($selectedSuggestions)];

    $response = [
        'message' => "$suggestionはどうでしょうか？"
    ];

    echo json_encode($response);
    exit;
}
?>
