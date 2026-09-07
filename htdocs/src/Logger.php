<?php
namespace App\Src;

class Logger{
public static function write(string $type,string $message):void
//第一引数ではログの種類($type)を指定 ex.[IMAGE]や[CREATE]など
//第二引数ではログ内容$messageを指定
{
  $timestamp =  date('Y-m-d H:i:s');
  //date:現在日時を指定フォーマット文字列で返す
  $line =  "[{$timestamp}][{$type}]{$message}"."\n";
  /**
   * ""内では変数が展開される/{}でここが変数名と区切っている
   * []は単なる文字列
   */
  file_put_contents(__DIR__ . '/../app.log',$line,FILE_APPEND);
  /**
   * file_put_contents:ファイルに文字列を書き込む関数
   * 第一引数:書き込み先のファイルパス/ 第二引数:書き込む内容/第三引数：オプション。FILE_APPEND=上書きせず末尾に追記
   */
}
}