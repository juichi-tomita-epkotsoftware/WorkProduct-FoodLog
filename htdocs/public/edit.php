<?php
error_reporting(-1);
require_once __DIR__ . '/../bootstrap.php';
$meals = $repo->getActive();
$ctrl->handleRequest($_POST, $_FILES);

$id =   (int)$_GET['id'] ?? 0;
// prg("detail.php?id={$id}");
$meal = $repo->findById($id);
if ($meal === null) {
    require_once __DIR__ . '/../views/header.php';
    echo "そのデータは存在しません<br><br>";
    echo '<a href="index.php">一覧に戻る</a>';
    exit;
}
require_once __DIR__ . '/../views/header.php';

?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<div class="card-header">
    <h2 class="head-edit">登録データ</h2>
</div>
<p>ご飯:<?= htmlspecialchars($meal['meal_name']) ?></p>
<p>時間:<?= htmlspecialchars($meal['food_cost'] ?? '(記載なし)') ?>円</p>
<p>費用:<?= htmlspecialchars($meal['food_cost'] ?? '(記載なし)') ?>円</p>
<p>コメント:<?= htmlspecialchars($meal['food_cost'] ?? '(記載なし)') ?>円</p>
<p>写真:<?= htmlspecialchars($meal['food_cost'] ?? '(記載なし)') ?>円</p>

<div class="card-header">
    <h2 class="head-edit">編集</h2>
</div>

<br>食べたものを変更<br>
<form action="detail.php?id=<?= $meal['id'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="edit_id" value="<?= html($meal['id']) ?>">
    <input type="text" name="inputName">
    <br>

    <label><input type="radio" name="inputTime" value="昼">昼ご飯</label>
    <label><input type="radio" name="inputTime" value="夜">夜ご飯</label><br>
    <label><input type="radio" name="inputTime" value="朝" checked>朝ご飯</label>
    <br>

    <br>料理時間は大体どれくらい？<br>
    <input type="number" name="inputMakingTime" placeholder="taiking time（munit）"><br>
    <br>

    <br>調理費用は大体どれくらい？<br>
    <input type="number" name="inputMakingCost" placeholder="cost（yen）"><br>
    <br>


    <br>コメント【次への改善点や買い物メモ】<br>
    <textarea name="inputComment" placeholder="Comment?"></textarea>
    <br>

    <br>写真(任意) <br>
    <input type="file" name="inputImage" accept="image/*">
    <br>


    <div class="col-12 clearfix">
        <div class="float-right test-box">
            <div class="btn-toolbar mb-3" role="toolbar">
                <div class="btn-group mr-2" role="group">
                    <button type="button" class="btn btn-primary" onclick="history.back()">戻る</button>
                </div>
                <div class="input-group">
                    <button type="submit"  class="btn btn-danger">決定</button>
                </div>
            </div>
        </div>
    </div>
</form>