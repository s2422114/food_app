<?php
// セッションを開始します。
session_start();

// ユーザーがログインしていない場合、ログインページにリダイレクトします。
if (!isset($_SESSION['ems'])) {
  header('Location: index.php');
  exit();
}

// セッションからユーザー名を取得します。
$username = $_SESSION['username']; // Assuming the username is stored in the session

// 提案する食事オプションを定義します。
$food_options = [
  'ガッツリ' => ['ジャンクフード', 'ラーメン', 'レストラン', '洋食', 'カレー', 'アジア・エスニック', '焼肉'],
  'そこそこ' => ['カフェ・喫茶店', '和食'],
  '軽め' => ['居酒屋', 'スイーツ', 'バー', '露店']
];

// フードタイプがPOSTされている場合、ランダムに食事オプションを返します。
if (isset($_POST['type'])) {
  $type = $_POST['type'];
  $response = '<strong>' . $food_options[$type][array_rand($food_options[$type])] . '</strong>はどうでしょうか？';
  echo json_encode(['response' => $response]);
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>チャットボット</title>
  <!-- 外部CSSファイルをリンクします。 -->
  <link rel="stylesheet" href="styles.css">
  <!-- 外部JavaScriptファイルをリンクします。 -->
  <script src="script.js" defer></script>
</head>
<body>
  <!-- ログアウトボタン -->
  <button class="logout" onclick="location.href='logout.php'">ログアウト</button>
  <!-- コンテナ -->
  <div class="container">
    <!-- チャットボックス -->
    <div class="chat-box">
      <!-- チャット出力 -->
      <div id="chat-output" class="chat-output"></div>
      <!-- ボタンのグループ -->
      <div class="buttons">
        <button class="btn-gutsuri" onclick="sendType('ガッツリ')">ガッツリ</button>
        <button class="btn-sokosoko" onclick="sendType('そこそこ')">そこそこ</button>
        <button class="btn-karume" onclick="sendType('軽め')">軽め</button>
      </div>
    </div>
  </div>
  <script>
    // フードタイプを送信する関数
    function sendType(type) {
      const buttons = document.querySelectorAll('.buttons button');
      buttons.forEach(button => button.disabled = true); // ボタンを無効化

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'chat.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          const chatOutput = document.getElementById('chat-output');
          // チャット出力にユーザー名と提案を追加します。
          chatOutput.innerHTML += `<p><strong>${username}:</strong> ${response.response}</p>`;
          // チャット出力のスクロールを最下部に移動します。
          chatOutput.scrollTop = chatOutput.scrollHeight;
          buttons.forEach(button => button.disabled = false); // ボタンを再度有効化
        }
      };
      // フードタイプを送信します。
      xhr.send('type=' + type);
    }

    // PHPからユーザー名をJavaScriptに渡します。
    const username = '<?php echo $username; ?>';
  </script>
</body>
</html>
