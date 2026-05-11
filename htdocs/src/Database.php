<?php

Class Database
{
    private static ?PDO $instance = null;   //静的プロパティで１つだけ保証

    private function __construct(){}

    /**
 * <定数とtry文を用いてDB接続>

 */
    public static function getInstance(): PDO
    {
        if  (self::$instance ==null){
            self::$instance = new PDO(DB_DNS,DB_USER,DB_PASSWORD);
            //DBに接続してPDOクラスからオブジェクト生成
            self::$instance -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            self::$instance -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            /**
             * setAttributeメソッド：PDOオブジェクトのエラー設定を行う。
             *      第一引数がATTR_ERRMODE:エラーの通知方法設定     ※ATTRはattribute（属性）から由来
             *               ATTR_EMULATE_PREPARES：PHPが模倣するか（プレースホルダの処理をPHPが行う）、MySQLが行うかの設定
             *
             * エミュレートをTrueにする場合の処理や動きを確認しきれていないため-5
             * エミュレート処理がfalse:プレースホルダの値を必ず文字列として扱える
            */

        }
        return self::$instance;
    }
}