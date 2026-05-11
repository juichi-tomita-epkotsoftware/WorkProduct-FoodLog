<?php
namespace App\Src;

class Logger{
/**ログ書き出し関数 */
public static function write(string $type,string $message):void
//第一引数ではログの種類を指定
//第二引数ではログ内容を指定
{
  $timestamp =  date('Y-m-d H:i:s');
  //date:現在日時を指定フォーマット文字列で返す
  $line =  "[{$timestamp}][{$type}]{$message}"."\n";
  /**
   * {}でここが変数名と区切っている
   * ""内では変数が展開される
   */
  file_put_contents(__DIR__ . '/../app.log',$line,FILE_APPEND);
  /**
   * file_put_contents:ファイルに文字列を書き込む
   * 第一引数:書き込み先のファイルパス/ 第二引数:書き込む内容/第三引数：オプション。FILE_APPEND=上書きせず末尾に追記
   */
}
}