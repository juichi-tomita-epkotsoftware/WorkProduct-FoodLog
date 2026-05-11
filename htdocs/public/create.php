<?php
require_once '../bootstrap.php';
$ctrl->handleRequest($_POST, $_FILES);   //登録のPOST処理
require_once __DIR__.'/../views/header.php';
?>
<h2><br>About Food &amp Cooking<br></h2>
<hr>
<em>&quot;Thou shouldst eat to live, not live to eat.&quot;</em>
<blockquote><br>(生きるために食べよ。食べるために生きるな)By ソクラテス</blockquote>

<div id="input_form">

    <br>&apos;What did you eat？&apos;<br>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <!-- ※formタグの属性は3つ　action:送信先ファイル　method:送信方法(POST:データ隠して送る)　enctype:データ形式 -->
        <input type="text" name="inputName" placeholder="What did you eat?"><br>
        <!-- meal_time登録フォーム -->
        <label><input type="radio" name="inputTime" value="朝" checked>morn.</label>
        <label><input type="radio" name="inputTime" value="昼">aft.</label>
        <label><input type="radio" name="inputTime" value="夜">eve.</label><br>

        <select name="inputTime">
            <option value="朝" selected>🌅 morn.</option>
            <option value="昼">☀️ aft.</option>
            <option value="夜">🌙 eve.</option>
        </select>

        <!-- cook_time登録フォーム -->
        <br>&apos;How long is cooking time?&apos;<br>
        <input type="number" name="inputMakingTime" placeholder="taiking time（munit）"><br>

        <!-- 1食費用登録フォーマ -->
        <br>&apos;How much is this cooking?&apos;<br>
        <input type="number" name="inputMakingCost" placeholder="cost（yen）"><br>

        <!-- コメント登録フォーム -->
        <br>&apos;Comment&apos;<br>
        <textarea name="inputComment" placeholder="Comment?"></textarea>
        <!--inputタグからtextareaタグへ変更-->
        <!-- 登録フォーム -->

        <br>&apos;Upload a photo of your meal &apos;<br>
        <input type="file" name="inputImage" accept="image/*">
        <input type="submit" value="register">
    </form>
</div>