<?php
use App\Repository\MealRepository;  //useはこのファイル限定のショートカット設定

require_once __DIR__ . '/src/Database.php'; //DB接続クラス
require_once __DIR__ . '/src/MealRepositoryInterface.php';  //インターフェイスファイルは先に読み込み
require_once __DIR__ . '/src/MealRepository.php';   //SQL処理クラス
require_once __DIR__ . '/src/ImageUploader.php';    //画像を保存するクラス
require_once __DIR__ . '/src/Logger.php';   //ログファイルへ追記するクラス
require_once __DIR__ . '/src/MealController.php';   //POST内のキーをみて振り分ける

$env = parse_ini_file(__DIR__.'/.env');  //.envファイルを読み込み、PHPの配列に変換する組み込み関数

define("DB_DNS", "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4");
define("DB_USER", $env['DB_USER']);
define("DB_PASSWORD",$env['DB_PASSWORD']);

//  * Laravelではdefine関数使わず.envファイルで情報管理する

$dbh = Database::getInstance();


$repo = new MealRepository($dbh); //SQLの窓口
$uploader = new ImageUploader ; //画像を保存する
$ctrl = new MealController($repo,$uploader);

function html(string|int|float|null $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function prg(string $url):void{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sep = str_contains($url, '?') ? '&' : '?';
    header("Location: {$url}{$sep}redirect=true");
    exit;}
}

