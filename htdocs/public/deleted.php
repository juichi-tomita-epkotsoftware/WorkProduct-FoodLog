<?php
require_once __DIR__ . '/../bootstrap.php';
$ctrl->handleRequest($_POST, $_FILES);

$sql = 'SELECT * FROM meals WHERE is_deleted IS TRUE';
$stmt = $dbh->query($sql);
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

prg('deleted.php');



require_once __DIR__ . '/../views/header.php'; ?>
<html>

<body>
  <h3>論理削除データ一覧</h3>


<div class="col-lg-8">
  <section class="card border-0 shadow-sm h-100">
    <div class="card-body">
      <h2 class="h6 mb-3">🍴 食事データ</h2>

      <table class="table table-hover align-middle small">
        <thead>
          <tr>
            <th>食べたもの</th>
            <th>登録日</th>
            <th>復元</th>
            <th>物理削除</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($meals as $meal): ?>
            <tr>
              <td><?= html($meal['meal_name']) ?></td>
              <td><?= html($meal['meal_date']) ?></td>
              <td>
                <form method="POST" class="m-0">
                  <input type="hidden" name="delete_id" value="<?= $meal['id'] ?>">
                  <button type="submit" name="delete_type" value="physical" class="btn btn-sm btn-outline-danger">物理削除🗑️</button>
                </form>
              </td>
              <td>
                <form method="POST">
                  <input type="hidden" name="restore_id" value="<?= $meal['id'] ?>">
                  <button type="submit" name="delete_type" value="physical">復元🧟</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

</html>
<?php require_once __DIR__ . '/../views/footer.php'; ?>