<?php

error_reporting(-1);
/**
 *  error_reporting関数：エラーレベルの設定
 *  -1またはE_ALLを引数に指定 → 全レベル表示
 *
 * セットで覚える
 * ini_set関数：エラーを画面に表示するか否かを決める
 * ini_set('display_errors',1);   画面に表示
 * ini_set('display_errors',0);   画面に出さない
 */

require_once __DIR__ . '/../bootstrap.php';

//MealControllerクラスのメソッド使用
$ctrl->handleRequest($_POST, $_FILES);   //ルーティング関数(URLと処理の紐づけ関数)呼び出し。
//newするたびに、異なる値をプロパティにいれたい(状態があるため)インスタンスで呼び出し
//$_POSTなどはPHPの組み込み変更。$_POSTはHTMLのmethod="POST"で送信されたデータが入る
//$_FILEAはファイル(画像や動画など)のアップロードデータが入る配列

//Repositoryクラスのメソッド使用
$meals = $repo->getActive();
//newするたびに、異なる一覧表示データをプロパティに入れたいためインスタンスで呼び出し

?>

<?php require_once __DIR__ . '/../views/header.php'; ?>

<h2>Home FoodLog</h2>
<h3><a href="create.php">新規登録</a></h3>


<section class="hero">
  <!-- sectionタグ：同じテーマのコンテンツを１つのまとまりとしてまとめるタグ（SEO対策） -->
  <img id="hero-img" src="img/cookingman.png">
</section>

<h3>Resistered Data</h3>

<table>
  <?php foreach ($meals as $meal): ?>
    <tr>
      <td><?= htmlspecialchars($meal['meal_name']) ?></td>
      <td><?= $meal['meal_date'] ?></td>
      <td>
        <a href="detail.php?id=<?= $meal['id'] ?>">詳細</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<!--ok mealsテーブル一覧表示する
    理解度:70%
    todo:query()とexecute()の動作の違い確認していない
    todo:フレームワークとの親和性や可読性のためforeach(...): endforeach;構文にコード修正
    -->

<h2><br>registered data<br></h2>
<hr>
<em>&quot;What gets measured, gets managed &quot;</em>
<blockquote><br>（測定されるものは、管理される。）By ピーター・ドラッカー（オーストリアの経営学者）<br></blockquote>
<?php
$sql = 'SELECT * FROM meals WHERE is_deleted IS NULL';
$stmt = $dbh->query($sql);
/*
            <PHPでSQLを実行するメソッドの違い>
            query():一覧処理など外部からの値を使わないSQL（SELECT * など）
            prepare()→execute():登録など外部の値を使うSQL
            */
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
            fetchAll()：SQLの実行結果をPHPの配列としてうけとる。
            まだ使い方不慣れ

            fetchAllの引数について（主に３つある）
            1.カラム名をキーにした連想配列
             fetchAll(PDO::FETCH_ASSOC);
            2.番号くぉキーにした連想配列
             fetchAll(PDO::FETCH_NUM);
            3.キーもカラム名もキーにした連想配列(デフォルト)
            (PDO::FETCH_BOTH);
              */

echo '<table border="1">';
echo '<thead><tr>
      <th>Id</th>
      <th>Date</th>
      <th>Timing</th>
      </th><th>Meal</th>
      <th>Takingtime</th>
      <th>Cost</th>
      <th>Memo</th>
      <th>L Delete</th>
      </tr></thead>';

echo '<tbody>';

foreach ($result as $val) {
  echo '<tr>';
  echo '<td>' . $val['id'] . '</td>';
  echo '<td>' . $val['meal_date'] . '</td>';
  echo '<td>' . $val['meal_time'] . '</td>';
  echo '<td>' . $val['meal_name'] . '</td>';
  echo '<td>' . $val['cook_time'] . '</td>';
  echo '<td>' . $val['food_cost'] . '</td>';
  echo '<td>' . $val['comment'] . '</td>';
  // ここから削除処理
  echo '<td>';
  echo   '<form method="POST">'; //データを隠してサーバへ送る箱
  echo     '<input type="hidden" name="delete_id" value="' . $val['id'] . '">';   //削除レコードのIDを非表示でPHPへ渡す
  echo     '<button type="submit" name="delete_type" value="logical">論理削除</button>';  //送信+削除種類を伝える。
  echo   '</form>';
  echo '</td>';
  // echo '<td>' . $val['logical delete'] . '</td>';
  echo '</tr>';
}

echo '</tbody></table>';
?>

<!-- ok物理削除操作テーブル -->
<br><br>物理削除<br>

<table border="1">
  <thead>
    <tr>
      <th>日付</th>
      <th>食事名</th>
      <th>操作</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($result as $val): ?>
      <tr>
        <td><?= $val['meal_date'] ?></td>
        <td><?= $val['meal_name'] ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="delete_id" value="<?= $val['id'] ?>">
            <button type="submit" name="delete_type" value="physical">物理削除</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>



<section id="works">
  <!-- class=""で名前つけるとページ内リンクがつくれない　JSではidを直接指定できる -->



  <script src="js/script.js"></script>

  </body>

  </html>

  <?php require_once __DIR__ . '/../views/footer.php'; ?>