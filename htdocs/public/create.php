<?php
require_once __DIR__ . '/../bootstrap.php';

$errors = [];
$old = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = $ctrl->handleRequest($_POST, $_FILES) ?? [];
    if (empty($errors)) {
        header("Location: index.php");
        exit;
    }
}
require_once __DIR__ . '/../views/header.php';
?>

<div class="col-lg-8">
    <section class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <h2 class="h6 mb-3">🍳 登録しましょう</h2>


            <form action="create.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="inputName" placeholder="食べたもの🍚(must)"><br></td>
                <?php if (isset($errors['inputName'])): ?>
                    <p><?= html($errors['inputName']) ?></p>
                <?php endif; ?>
                <br><br>


                <label><input type="radio" name="inputTime" value="朝">朝ご飯🐔</label><br>
                <label><input type="radio" name="inputTime" value="昼">昼ご飯🐈</label><br>
                <label><input type="radio" name="inputTime" value="夜">夜ご飯🦉</label><br>
                <br>
                </td>
                <?php if (isset($errors['inputTime'])): ?>
                    <p><?= htmlspecialchars($errors['inputTime']) ?></p>
                <?php endif; ?>


                <input type="number" name="inputMakingTime" placeholder="調理にかかった時間⏱(must)"><br>
                <?php if (isset($errors['inputMakingTime'])): ?>
                    <p><?= htmlspecialchars($errors['inputMakingTime']) ?></p>
                <?php endif; ?>
                </td>
                <br>


                <input type="number" name="inputMakingCost" placeholder="材料費💰(must)"><br>
                <?php if (isset($errors['inputMakingCost'])): ?>
                    <p><?= htmlspecialchars($errors['inputMakingCost']) ?></p>
                <?php endif; ?>
                </td><br>

                </td>
                <textarea name="inputComment" placeholder="コメント💬(must)"></textarea>
                <?php if (isset($errors['Comment'])): ?>
                    <p><?= htmlspecialchars($errors['Comment']) ?></p>
                <?php endif; ?>
                <br>

                <br>写真📷(option)<br>
                <input type="file" name="inputImage" accept="image/*">
                <br>

                <input type="submit" value="登録">
                <br>

        </div>





        </form>
</div>