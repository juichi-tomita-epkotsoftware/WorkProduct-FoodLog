<?php
use App\Repository\MealRepository;

require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/MealRepositoryInterface.php';
require_once __DIR__ . '/src/MealRepository.php';
require_once __DIR__ . '/src/ImageUploader.php';
require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/src/MealController.php';
/**
 *  クラス化した他ファイル(srcフォルダ内ファイル)を読み込む
 * 　※require_onceは関数ではなく言語構造(echoなどと同種)
 */

$env = parse_ini_file('.env');  //.envファイルを読み込み、PHPの配列に変換する組み込み関数
define("DB_DNS", "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8");
define("DB_USER", $env['DB_USER']);
define("DB_PASSWORD",$env['DB_PASSWORD']);
/**
 * DB情報を定数として定義
 *
 * 参考
 * define:定数を定義する  ※define("定数名",値);
 * PSRにより定数名は全て大文字にする
 * Laravelではdefine関数使わず.envファイルで情報管理する
 */

$dbh = Database::getInstance();
/**
 * DB接続(自作のクラスを使用)
 * newをしない理由：Databaseクラスは不可を減らすためインスタンスを１つしか作らせない設計がされているため。
 * ※staticメソッドのためインスタンス不要で::で関数呼べる。
 */
$repo = new MealRepository($dbh); //$dbhという状態を持たせるからインスタンス化
$uploader = new ImageUploader ;   //クラス内のコンストラクトで定義された引数を状態に持つためインスタンス化
$ctrl = new MealController($repo,$uploader);  //$repoと$uploaderという状態を持たせるためnewでインスタンス化