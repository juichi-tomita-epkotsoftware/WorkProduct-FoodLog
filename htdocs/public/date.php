<?php
require_once __DIR__ . '/../bootstrap.php';


require_once __DIR__ . '/../views/header.php';

$tz    = new DateTimeZone('Asia/Tokyo');
$today = new DateTimeImmutable('today', $tz);
$from = $today->modify('monday this week')->format('Y-m-d');
$to   = $today->modify('sunday this week')->format('Y-m-d');

$ctrl->handleRequest($_POST, $_FILES);

$datemeals = [];
if (isset($_GET['from'], $_GET['to'])) {
    $datemeals = $repo->findByDateRange($_GET['from'], $_GET['to'])??[];
};

?>


<div id="input_form">

    <form action="date.php" method="GET">
        <input type="hidden" name="from" value="<?= html($from) ?>">
        <input type="hidden" name="to" value="<?= html($to) ?>">

        <div class="input-group">
            <button type="submit" class="btn btn-danger">今週のデータをみる</button>
            <br>
        </div>
    </form>
</div>

<br><br>
<div class="col-lg-8">
    <section class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <h2 class="h6 mb-3">🍴 食事データ</h2>

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
                    <?php foreach ($datemeals as $datemeal): ?>
                        <?php if (isset($_GET['from']) && empty($datemeals)): ?>今週のデータはありません<?php endif; ?>
                        <tr>
                            <td><?= html($datemeal['meal_name']) ?></td>
                            <td><?= html($datemeal['meal_date']) ?></td>
                            <td><a href="detail.php?id=<?= $datemeal['id'] ?>">詳細</a></td>
                            <td>
                                <form method="POST" class="m-0" action="date.php?from=<?= html($from) ?>&to=<?= html($to) ?>">
                                    <input type="hidden" name="delete_id" value="<?= $datemeal['id'] ?>">
                                    <button type="submit" name="delete_type" value="logical" class="btn btn-sm btn-outline-danger">🗑️</button>
                                </form>
                            </td>
                            <td><a href="edit.php?id=<?= $datemeal['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a></td>
                        </tr>
                    <?php endforeach; ?>
    </section>
</div>
</tbody>
</table>

</tbody>
</div>