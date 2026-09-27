<?php
require_once __DIR__.'/../bootstrap.php';

// IDはデータからではなくURLから取得している。
$id =   (int)$_GET['id']??'0';
$ctrl->handleRequest($_POST, $_FILES);
$meal = $repo->findById($id);
if($meal === null){
    require_once __DIR__.'/../views/header.php';
    echo "そのデータは存在しません<br><br>";
    echo '<a href="index.php">一覧に戻る</a>';
    exit;
}


require_once __DIR__.'/../views/header.php';

?>

<h2><?= htmlspecialchars($meal['meal_name']) ?></h2>
<p>mealname:<?= htmlspecialchars($meal['meal_name']) ?></p>
<p>cost:<?= htmlspecialchars($meal['food_cost']??'(記載なし)') ?>円</p>



<a href="index.php">←一覧に戻る</a>

<?php require_once __DIR__.'/../views/footer.php';?>