<?php
// 環境変数をロードする
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ホットペッパーAPIキーを取得
$apiKey = $_ENV['HOTPEPPER_API_KEY'];

// キーワードの取得
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : 'ラーメン';

// ホットペッパー API リクエスト
$baseUrl = 'https://webservice.recruit.co.jp/hotpepper/gourmet/v1/';
$params = [
    'key' => $apiKey,
    'keyword' => $keyword,
    'count' => 10,
    'format' => 'json',
];

// API リクエストを送信
$url = $baseUrl . '?' . http_build_query($params);
$response = file_get_contents($url);
$data = json_decode($response, true);

// 店舗情報を取得
$shops = $data['results']['shop'] ?? [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>店舗検索結果</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .shop {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>「<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>」の検索結果</h1>
    <?php if (count($shops) > 0): ?>
        <?php foreach ($shops as $shop): ?>
            <div class="shop">
                <h2><?= htmlspecialchars($shop['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p>住所: <?= htmlspecialchars($shop['address'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>アクセス: <?= htmlspecialchars($shop['access'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>ジャンル: <?= htmlspecialchars($shop['genre']['name'] ?? '不明', ENT_QUOTES, 'UTF-8') ?></p>
                <p><a href="<?= htmlspecialchars($shop['urls']['pc'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">詳細はこちら</a></p>
                <?php if (!empty($shop['photo']['pc']['l'])): ?>
                    <img src="<?= htmlspecialchars($shop['photo']['pc']['l'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($shop['name'], ENT_QUOTES, 'UTF-8') ?>" style="max-width: 200px;">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>該当する店舗情報が見つかりませんでした。</p>
    <?php endif; ?>
</body>
</html>
