<?php
error_reporting(-1);
require_once __DIR__ . '/../bootstrap.php';
$meals =[];

$ctrl->handleRequest($_POST, $_FILES);
prg('index.php');

//検索あればgetSearchedへ。無ければgetActiveへ
$keyword = trim($_GET['search']??'');
if($keyword !== ''){
  $meals = $repo->getSearched($keyword);
}else{
  $meals = $repo->getActive();
}

require_once __DIR__ . '/../views/header.php';
?>

<!-- 検索画面+今日の日付 -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <form class="input-group w-50" action="" method="get">
    <input type="text" name="search" class="form-control rounded-start-pill" value="<?php html($keyword) ?>" placeholder="食事名で検索…">
    <button type="submit" class="btn btn-outline-secondary bg-white rounded-end-pill px-4">検索</button>
  </form>

  <span class="badge rounded-pill text-bg-light fs-6 px-3 py-2">📅 <?= date('Y.m.d') ?></span>
</div>

<div class="row g-4">

  <div class="col-lg-4 d-flex flex-column gap-4">

    <section class="card border-0 shadow-sm">
      <div class="card-body">
        <h2 class="h6 mb-3">📷 最新の食事記録</h2>
        <img id="hero-img" src="img/cookingman.png" class="rounded hero-img" alt="料理の写真">
      </div>
    </section>

    <section class="card border-0 shadow-sm">
      <div class="card-body">
        <h2 class="h6 mb-3">🔥 目標食費</h2>
        <div class="progress" role="progressbar">
          <div class="progress-bar bg-danger" style="width:69%"></div>
        </div>
      </div>
    </section>

    <section class="card border-0 shadow-sm">
      <div class="card-body">
        <h2 class="h6 mb-3">⭐ AI分析</h2>
        <div class="d-flex justify-content-around text-center small">
        </div>
      </div>
    </section>

  </div>

  <!-- 右となり：食事データ -->
  <div class="col-lg-8">
    <section class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h2 class="h6 mb-3">🍴 食事データ(一覧)</h2>

        <table class="table table-hover align-middle small">
          <thead>
            <tr>
              <th>食べたもの</th>
              <th>登録日</th>
              <th>詳細</th>
              <th>削除</th>
              <th>編集</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($meals as $meal): ?>
              <tr>
                <td><?= html($meal['meal_name']) ?></td>
                <td><?= html($meal['meal_date']) ?></td>
                <td><a href="detail.php?id=<?= $meal['id'] ?>">詳細</a></td>
                <td>
                  <form method="POST" class="m-0">
                    <input type="hidden" name="delete_id" value="<?= $meal['id'] ?>">
                    <button type="submit" name="delete_type" value="logical" class="btn btn-sm btn-outline-danger">🗑️</button>
                  </form>
                </td>
                <td><a href="edit.php?id=<?= $meal['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
        <script src="js/script.js"></script>

        <?php require_once __DIR__ . '/../views/footer.php'; ?>