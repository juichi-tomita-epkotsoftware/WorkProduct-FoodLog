<?php
require_once __DIR__.'/../bootstrap.php';

//URLの?id=3を受け取る
$id =   (int)$_GET['id'];
$meal = $repo->findById($id);

require_once __DIR__.'/../views/header.php';
?>

<h2><?= htmlspecialchars($meal['meal_name']) ?></h2>
<p>mealname:<?= htmlspecialchars($meal['meal_name']) ?></p>
<p>cost:<?= htmlspecialchars($meal['food_cost']??'(記載なし)') ?>円</p>

<a href="index.php">←一覧に戻る</a>

<?php require_once __DIR__.'/../views/footer.php';?>