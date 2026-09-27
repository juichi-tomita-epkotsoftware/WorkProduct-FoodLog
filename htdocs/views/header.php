<?php
require_once __DIR__ .'/../bootstrap.php';
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<?php
require_once __DIR__ . '/../bootstrap.php';
$current = basename($_SERVER['SCRIPT_NAME']); // 今のファイル名（例：index.php）
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Food Log|食事管理アプリ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
<div class="d-flex min-vh-100">
  <header class="sidebar p-4">
    <h1 class="logo mb-4">Food Log</h1>
    <nav>
      <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item"><a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="/public/index.php">🏠 一覧</a></li>
        <li class="nav-item"><a class="nav-link <?= $current === 'create.php' ? 'active' : '' ?>" href="/public/create.php">📝 登録</a></li>
        <li class="nav-item"><a class="nav-link <?= $current === 'deleted.php' ? 'active' : '' ?>" href="/public/deleted.php">🗑️ 消去済み</a></li>
        <li class="nav-item"><a class="nav-link <?= $current === 'date.php' ? 'active' : '' ?>" href="/public/date.php">📅 今週の記録</a></li>
      </ul>
    </nav>
  </header>

  <main class="flex-grow-1 p-4">