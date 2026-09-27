<?php

Class Database
{
    private static ?PDO $instance = null;   //静的プロパティで１つだけ保証
    private function __construct(){}

    //PDO接続
    public static function getInstance(): PDO
    {
        if  (self::$instance ==null){
            self::$instance = new PDO(DB_DNS,DB_USER,DB_PASSWORD);
            //DBに接続してPDOクラスからオブジェクト生成
            self::$instance -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            self::$instance -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        }
        return self::$instance;
    }
}