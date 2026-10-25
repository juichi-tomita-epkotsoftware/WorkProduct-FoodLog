<?php

Class ImageUploader
{
    private string $uploadDir;  //サーバ上の絶対パス(OSから見たファイルの実際の場所)
    private string $publicPath; //Web公開用の相対パス(ブラウザからアクセスする際のURL用)

    public function __construct(
        string $uploadDir = '/var/www/html/uploads',
        string $publicPath = 'uploads/'
    ){
        $this->uploadDir = $uploadDir;
        $this->publicPath = $publicPath;
    }

    public function upload(array $file):?string
    {
        if(empty($file['name'])){
            return null;
        }

        $fileName = basename($file['name']);
        //basename()：ファイルパスからファイル名だけを取り出す関数
        $savePath = $this ->uploadDir . '/' .$fileName;

        if (!move_uploaded_file($file['tmp_name'],$savePath)){
            //move_uoloaded_file：HTTPアップロードで受け取った一時ファイルを指定場所へ移動
            throw new RuntimeException("画像の保存に失敗しました:{$fileName}");
        }

        return $this->publicPath . $fileName;
        //Web用の相対パスを返す　例: uploads/photo.jpg
    }
}