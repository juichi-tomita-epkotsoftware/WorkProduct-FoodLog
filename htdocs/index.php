<?php
//Ok エラー表示設定
error_reporting(-1);

/*ok データベース設定 */
define("DB_DNS", "mysql:host=mysql;dbname=meal_manager;charset=utf8");
define("DB_USER", "root");
define("DB_PASSWORD", "root");
// define(定数を定義する関数)

/* データベースへ接続 理解度60％　PDO設定やプリペアドステートメントが何かよく理解できていない*/
try {
  $dbh = new PDO(DB_DNS, DB_USER, DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //PDO(PHP Data Objects):PHPでDBをオブジェクトとして扱う機能でクラスの一種。
  //setAttributeメソッド：PDOオブジェクトの動作設定を変更すること。attributeはタグの属性のこと。　setAttribute(属性, 値)
  //ATTR_ERRMODE：「エラーの通知方法」という属性/ERRMODE_EXCEPTION：「例外を投げる」という値
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  //不明点：ATTR_EMULATE_PREPARES    「プリペアドステートメントをエミュレートするか(再現するか)」という設定項目
} catch (PDOException $e) {

  echo $e->getMessage();
}

/* ごはんの名前+登録時間+をデータベースへ登録 理解度50％　SQLインジェクション対策のプレースホルダやアロー*/
if (
  !empty($_POST['inputName'])
) {
  try {
    $sql = 'INSERT INTO meals(meal_name, meal_date,meal_time,meal_making_time)
            VALUES(:ONAMAE, CURDATE(),:TIME,:MAKING_TIME)';
    // VALUESはどうゆう意味。なぜCURCATEは：がないのか
    //:ONAMAE,:GENDER   SQLインジェクション対策のプレースホルダ
    $stmt = $dbh->prepare($sql);
    //prepareメソッド：SQLを実行準備状態にする　//$stmt ステートメントの略。声明という意味。
    $stmt->bindValue(':ONAMAE', $_POST['inputName'],   PDO::PARAM_STR);
    $stmt->bindValue(':TIME', $_POST['inputTime'],   PDO::PARAM_STR);
    $stmt->bindValue(':MAKING_TIME', $_POST['inputMakingTime'],   PDO::PARAM_STR);
    //bindValueメソッド：プレースホルダーにフォームから送られたデータをセット
    //bindValue は3つの引数を持っています。$stmt->bindValue( 'プレースホルダ名',  '実際の値',  'データ型' );
    $stmt->execute();
    //SQLで実行 "ここで初めてDBにSQLが送られる"
    //prepare → bindValue → execute    この3点セットは超重要です。
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

/* 移動した要素の座標をデータベースへ登録 todo:機能していないので要確認*/
if (isset($_POST['sort_order'])) {
  try {
    $sql  = 'UPDATE `meals` SET `sort_order` = :SORT WHERE,  `id` = :NUMBER';
    //WHERE id = :NUMBER が無いと全行更新事故になるので超重要！
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':SORT', $_POST['sort_order'], PDO::PARAM_INT);
    $stmt->bindValue(':NUMBER', $_POST['id'],   PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

/*
【処理内容】
論理消去と物理消去処理 論理削除ならデータに書き込み。
違えば指定idにて物理削除
【理解度：60％】
理由：prepare bindValueの流れがよく分からない
*/
if (!empty($_POST['delete_id'])) {
  try {
    if ($_POST['delete_type'] === 'logical') {
      /*
      論理削除：is_deletedに現在時刻を入れる
      IDはプレースホルダー　NOW() はMySQLの関数で現在の日時を返します。
      logical は論理削除のこと。physicalが物理削除
      WHERE　絞り込み条件
       */
      $sql = 'UPDATE meals SET is_deleted = NOW() WHERE id = :ID';
    } else {
      /*
      物理削除：レコードごと消す（WHERE id = :ID が超重要で、これがないと全レコードが消えます。)
      */
      $sql = 'DELETE FROM meals WHERE id = :ID';
    }
    $stmt = $dbh->prepare($sql);
    //SQLを実行状態にする
    $stmt->bindValue(':ID', $_POST['delete_id'], PDO::PARAM_INT);
    //プレースホルダに実際の値をセット　PDO::PARAM_INT（整数として扱う）これがSQLインジェクション対策になっている
    $stmt->execute();
    //ここで初めてDBにSQLが送られ実行される
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Food Log</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <header>
    <h1 class="logo">Food Log</h1>
    <nav>
      <!-- navタグ:ページ内のナビゲーションリンク（移動用リンク）をまとめるためのタグ -->
      <ul>
        <li><a href="#gallery">GALLERY</a></li>
        <li><a href="#profile">Nutrition Memo</a></li>
        <li><a href="#works">Data Analysis</a></li>
        <li><a href="#news">Challenge Memo</a></li>
      </ul>
    </nav>
  </header>

  <!-- meal_name登録フォーム -->
  <br>
  <div id="input_form">
    <form action="index.php" method="POST">
      <input type="text" name="inputName" placeholder="どんなご飯を食べましたか">

      <!-- meal_time登録フォーム -->
      <br>いつ食べましたか？<br>
      <label><input type="radio" name="inputTime" value="朝" checked>朝</label>
      <label><input type="radio" name="inputTime" value="昼">昼</label>
      <label><input type="radio" name="inputTime" value="夜">夜</label>
      <!-- labelタグクリックしやすくして操作性をあげる -->

      <!-- meal_making_time登録フォーム -->
      <br>料理時間はどれくらいですか？<br>
      <label><input type="radio" name="inputMakingTime" value="15分以下" checked>15分以下</label>
      <label><input type="radio" name="inputMakingTime" value="30分以下">30分以下</label>
      <label><input type="radio" name="inputMakingTime" value="30分以上">30分以上</label>
      <input type="submit" value="登録">
    </form>
  </div>

  <!-- mealsテーブルを一覧表示するPHP -->
  <?php
  echo "<br><br>登録データ<br>";
  $sql = 'SELECT * FROM meals WHERE is_deleted IS NULL';
  //絞り込みで論理削除されているもの以外を取得
  $stmt = $dbh->query($sql);
  /*
        <PHPでSQLを実行するメソッドの違い>
        query():外部からの値を使わないSQL（SELECT * など）
        prepare()→execute():フォームからの値など外部の値を使うSQL
        */
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  /*
        fetchAll()：SQLの実行結果をPHPの配列としてうけとる。
        (PDO::FETCH_ASSOC)の指定でカラム名をキーにした連想配列になる。
         */

  //上記で整理した配列($result)を$valに入れながら繰り返す
  //result配列をval変数に入れる
  foreach ($result as $val) {
    echo '<table border="1">';
    echo '<thead><tr><th>meal_date</th><th>When(meal_time)</th><th>meal_name</th><th>meal_making_time</th></tr></thead>';
    echo '<tbody>';

    foreach ($result as $val) {
      echo '<tr>';
      echo '<td>' . $val['meal_date'] . '</td>';
      echo '<td>' . $val['meal_time'] . '</td>';
      echo '<td>' . $val['meal_name'] . '</td>';
      echo '<td>' . $val['meal_making_time'] . '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
  }
  ?>

  <!-- 論理消去と物理削除　操作テーブル -->
  <br><br>論理削除と物理削除<br>

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
              <button type="submit" name="delete_type" value="logical">論理削除</button>
              <button type="submit" name="delete_type" value="physical">物理削除</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!--
  mealsテーブルを一覧表示するPHP
  todo データ数分、テーブルが表示される
  -->
  <?php
  echo "<br><br>論理削除したデータ<br>";
  $sql = 'SELECT * FROM meals WHERE is_deleted IS TRUE';
  //絞り込みで論理削除されているものを取得
  $stmt = $dbh->query($sql);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<table border="1">';
    echo '<thead><tr><th>meal_date</th><th>When(meal_time)</th><th>meal_name</th><th>meal_making_time</th></tr></thead>';
    echo '<tbody>';
    foreach ($result as $val) {
      echo '<tr>';
      echo '<td>' . $val['meal_date'] . '</td>';
      echo '<td>' . $val['meal_time'] . '</td>';
      echo '<td>' . $val['meal_name'] . '</td>';
      echo '<td>' . $val['meal_making_time'] . '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';

  ?>

  <section class="hero">
    <!-- sectionタグ：同じテーマのコンテンツを１つのまとまりとしてまとめるタグ（SEO対策） -->
    <img id="hero-img" src="img/cookingman.png">
  </section>

  <table border="1">
    <!-- borderはテーブルの線の太さを表す -->
    <thead>
      <!-- thead:表の見出し（古い書き方） -->
      <tr>
        <th>meal_date</th>
        <th>meal_name</th>
        <th>making_time</th>
        <th>food_cost</th>
      </tr>
    </thead>

    <tbody>
      <!--
    tbody:表の中身
    trタグ：Table Row 表の一行をつくる
    tdタグ：Table　Data　表のデータ
    thタグ：Table　Header　見出しセル
    -->
      <tr>
        <td>3/12(Thu)morning</td>
        <td>みそ汁と半分バナナ</td>
        <td>10 minutes</td>
        <td></td>
      </tr>
      <tr>
        <td>3/12(Thu)lunch</td>
        <td>うどん</td>
        <td>12 minutes</td>
      </tr>
      <tr>
        <td>3/12(Thu)dinner</td>
        <td>うどん（海苔トッピング）</td>
        <td>12 minutes</td>
      </tr>
      <tr>
        <td>3/13(Fry)morning</td>
        <td>ご飯ウインナー目玉焼きみそ汁</td>
        <td>15 minutes</td>
      </tr>
      <tr>
        <td>3/13(Fry)lunch</td>
        <td>カップ麺(スーパーカップ)</td>
        <td>5 minutes</td>
      </tr>
      <tr>
        <td>3/13(Fry)dinner</td>
        <td>うどんとヨーグルト</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/14(Sat)morning</td>
        <td>卵かけご飯としゃぶ肉</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/14(Sat)lunch</td>
        <td>ネギとろ丼とキムチみそ汁</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/14(Sat)dinner</td>
        <td>ゆで卵とピザトースト</td>
        <td>18 minutes</td>
      </tr>
      <tr>
        <td>3/15(Sun)morning</td>
        <td>ピザトースト</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/15(Sun)lunch</td>
        <td>キムチチーズうどん</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/15(Sun)dinner</td>
        <td>ねぎとろうどん</td>
        <td>15 minutes</td>
      </tr>
      <tr>
        <td>3/16(Mon)morning</td>
        <td>みそ汁</td>
        <td>8 minutes</td>
      </tr>
      <tr>
        <td>3/16(Mon)lunch</td>
        <td>ピリ辛うどん</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/16(Mon)dinner</td>
        <td>目玉焼きピリ辛うどん</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/17(Mon)mrning</td>
        <td>ワッフルとみそ汁</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/17(Mon)lunch</td>
        <td>バタートーストと目玉焼きピリ辛うどん</td>
        <td>10 minutes</td>
      </tr>
      <tr>
        <td>3/17(Mon)dinner</td>
        <td>しゃぶしゃぶと白米</td>
        <td>15 minutes</td>
      </tr>
      <tr>
        <td>3/18(Tues)morning</td>
        <td>納豆卵ごはんとウインナー</td>
        <td>15 minutes</td>
      </tr>
      <tr>
        <td>3/18(Tues)lunch</td>
        <td>ガーリックトーストプレート</td>
        <td>15 minutes</td>
      </tr>
      <tr>
        <td>3/18(Tues)dinner</td>
        <td>豚しゃぶプレート</td>
        <td>15 minutes</td>
      </tr>
    </tbody>
  </table>

  <section id="gallery">
    <h2>Gallery</h2>
    <div class="grid">
      <!-- クラスは何度もページ内で用いれるから編集のしやすさから画像をまとめるときはclassが向いている -->
      <img src="img/cooking/appetizer_salada.jpg">
      <img src="img/cooking/appetizer_salada2.jpg">
      <img src="img/cooking/bonito.jpg">
      <img src="img/cooking/bonito2.jpg">
      <img src="img/cooking/chiken_marinade.jpg">
      <img src="img/cooking/coolnoodle.jpg">
      <img src="img/cooking/frenchtoste.jpg">
      <img src="img/cooking/frenchtoste2.jpg">
      <img src="img/cooking/monkfish_pot.jpg">
      <img src="img/cooking/normalfrench.jpg">
      <img src="img/cooking/normalfrench2.jpg">
      <img src="img/cooking/pasta.jpg">
      <img src="img/cooking/pizza.jpg">
      <img src="img/cooking/smoothie.jpg">
      <img src="img/cooking/three_pasta.jpg">
      <img src="img/cooking/three_pasta2.jpg">
      <img src="img/cooking/tomata_pasta.jpg">
      <img src="img/cooking/tomato_pasta2.jpg">

    </div>
  </section>

  <section id="profile">
    <h2>Nutrition Memo(文章の編集機能加えたい)</h2>
    <ul>
      <li>DHA(オメガ3脂肪酸)：脳と目に良い脂。 </li>
      <li>EPA(オメガ3脂肪酸)：血液につよい脂。生活習慣病予防。 </li>
      <li>ビタミンB群（特にB1・B12・葉酸）：糖をエネルギーに変えて脳に供給。集中力の維持に必須。
        ex.豚肉・納豆・卵・玄米</li>
      <li>トリプトファン（アミノ酸）：
        「やる気ホルモン」セロトニンの材料。ex.バナナ・豆腐・納豆・卵</li>
      <li>クエン酸・ビタミンC:疲労物質を分解。勉強後の回復に。
        → レモン・梅干し・ブロッコリー（この時期は花粉症対策にも◎）(ビタミンCは鉄の吸収を効率化)</li>
      <li>鉄分：不足すると集中力・記憶力が落ちる。
        → ほうれん草・小松菜・豆腐・納豆（カルシウム,タンニン(コーヒー)が吸収を阻害）
      </li>
    </ul>
  </section>

  <section id="works">
    <!-- class=""で名前つけるとページ内リンクがつくれない　JSではidを直接指定できる -->
    <h2>Other Memo</h2>
    <div class="grid">
      <img src="img/work1.jpg">
      <img src="img/work2.jpg">
      <img src="img/work3.jpg">
    </div>
  </section>

  <section id="news">
    <h2>Data Analysis(MySQLを用いた簡易的なデータ分析をしたい)</h2>
    <ul>
      <li>食費の折れ線グラフ</li>
      <li>食費合計</li>
      <li>一般男性の食費平均</li>
    </ul>
  </section>

  <footer>
    <p>© tomita</p>
  </footer>

  <!-- <script src="js/script.js"></script> -->

</body>

</html>




