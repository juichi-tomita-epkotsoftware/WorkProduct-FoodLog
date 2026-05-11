<?php
require_once '../bootstrap.php';
require_once __DIR__.'/../views/header.php';
?>
<h2><br>Logical Deleted Date<br></h2>

  <!--ok論理削除一覧と物理消去処理-->
  <?php
  $sql = 'SELECT * FROM meals WHERE is_deleted IS TRUE';
  //絞り込みで論理削除されているものを取得
  $stmt = $dbh->query($sql);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo '<table border="1">';
  echo '<thead><tr><th>meal_date</th><th>When(meal_time)</th><th>meal_name</th><th>cook_time</th></tr></thead>';
  echo '<tbody>';
  foreach ($result as $val) {
    echo '<tr>';
    echo '<td>' . $val['meal_date'] . '</td>';
    echo '<td>' . $val['meal_time'] . '</td>';
    echo '<td>' . $val['meal_name'] . '</td>';
    echo '<td>' . $val['cook_time'] . '</td>';
    echo '<td>';
    echo   '<form method="POST">';
    echo     '<input type="hidden" name="restore_id" value="' . $val['id'] . '">';
    echo     '<button type="submit">復元</button>';
    echo   '</form>';
    echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
  ?>