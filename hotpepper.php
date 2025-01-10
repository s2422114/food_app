<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// APIキーを取得
$apiKey = $_ENV['HOTPEPPER_API_KEY'];

// セッションから選択されたフードジャンルを取得
$keyword = $_SESSION['selected_food'] ?? 'ラーメン'; // セッションからジャンルを取得

// 現在地のデフォルト値
$lat = $_GET['lat'] ?? '35.681236';
$lng = $_GET['lng'] ?? '139.767125';

// ホットペッパーAPIリクエスト
$baseUrl = 'https://webservice.recruit.co.jp/hotpepper/gourmet/v1/';
$params = [
    'key' => $apiKey,
    'lat' => $lat,
    'lng' => $lng,
    'keyword' => $keyword,
    'count' => 100,
    'range' => 5,
    'format' => 'json',
];
$url = $baseUrl . '?' . http_build_query($params);
$response = file_get_contents($url);
$data = json_decode($response, true);
$shops = $data['results']['shop'] ?? [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>店舗検索結果</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .shop {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px auto;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <h1>「<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>」の店舗検索結果</h1>
    <form method="GET" action="">
        <input type="hidden" name="lat" id="lat" value="">
        <input type="hidden" name="lng" id="lng" value="">
        <button type="submit">現在地から検索</button>
    </form>
    <?php if (count($shops) > 0): ?>
        <?php foreach ($shops as $shop): ?>
            <div class="shop">
                <h2><?= htmlspecialchars($shop['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p>住所: <?= htmlspecialchars($shop['address'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>アクセス: <?= htmlspecialchars($shop['access'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>ジャンル: <?= htmlspecialchars($shop['genre']['name'] ?? '不明', ENT_QUOTES, 'UTF-8') ?></p>
                <p>予算: <?= htmlspecialchars($shop['budget']['average'] ?? '不明', ENT_QUOTES, 'UTF-8') ?></p>
                <p><a href="<?= htmlspecialchars($shop['urls']['pc'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">詳細はこちら</a></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>該当する店舗が見つかりませんでした。</p>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                    },
                    (error) => {
                        alert('現在地を取得できませんでした。');
                    }
                );
            } else {
                alert('お使いのブラウザでは位置情報がサポートされていません。');
            }
        });
    </script>
</body>
</html>
