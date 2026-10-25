<?php
require_once __DIR__.'/../bootstrap.php';

// IDはデータからではなくURLから取得
$id =   (int)$_GET['id']??'0';
$ctrl->handleRequest($_POST, $_FILES);
$meal = $repo->findById($id);
if($meal === null){
    require_once __DIR__.'/../views/header.php';
    echo "そのデータは存在しません<br><br>";
    echo '<a href="index.php">一覧に戻る</a>';
    exit;
}

$imageUrl = !empty($meal['image_path']) ? $meal['image_path'] : null;
$cost     = $meal['food_cost'] ?? null;
$cookTime = $meal['cook_time'] ?? null;
$comment  = $meal['comment'] ?? '';
$date     = $meal['meal_date'] ?? null;
$mealTime = $meal['meal_time'] ?? null;
$mealTimeLabels = [
    1 => '朝食',
    2 => '昼食',
    3 => '夕食',
];
$mealTimeLabel = $mealTimeLabels[$mealTime] ?? $mealTime;

require_once __DIR__.'/../views/header.php';

?>

<article class="meal-detail">
    <?php if ($imageUrl !== null): ?>
        <img class="meal-detail__image"
             src="<?= htmlspecialchars($imageUrl) ?>"
             alt="<?= htmlspecialchars($meal['meal_name']) ?>">
    <?php else: ?>
        <div class="meal-detail__noimage">画像は登録されていません</div>
    <?php endif; ?>

    <div class="meal-detail__body">
        <?php if (!empty($date)): ?>
            <p class="meal-detail__date"><?= htmlspecialchars($date) ?></p>
        <?php endif; ?>

        <h2 class="meal-detail__title"><?= htmlspecialchars($meal['meal_name']) ?></h2>

        <div class="meal-detail__badges">

            <span class="meal-detail__badge meal-detail__badge--meal">
                <small>食事区分</small><?= ($mealTimeLabel !== null && $mealTimeLabel !== '') ? htmlspecialchars((string)$mealTimeLabel) : '(記載なし)' ?>
            </span>
            <span class="meal-detail__badge meal-detail__badge--cost">
                <small>調理費用</small><?= ($cost !== null && $cost !== '') ? htmlspecialchars((string)$cost) . '円' : '(記載なし)' ?>
            </span>
            <span class="meal-detail__badge meal-detail__badge--time">
                <small>調理時間</small><?= ($cookTime !== null && $cookTime !== '') ? htmlspecialchars((string)$cookTime) . '分' : '(記載なし)' ?>
            </span>
        </div>

        <?php if ($comment !== ''): ?>
            <p class="meal-detail__comment"><?= htmlspecialchars($comment) ?></p>
        <?php endif; ?>

        <div>
            <a class="meal-detail__back" href="index.php">← 一覧に戻る</a>
        </div>
    </div>
</article>


<?php require_once __DIR__.'/../views/footer.php';?>