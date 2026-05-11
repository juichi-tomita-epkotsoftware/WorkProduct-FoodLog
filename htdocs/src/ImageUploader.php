<?php

Class ImageUploader
{
    private string $uploadDir;  //サーバ上の絶対パス(OSから見たファイルの実際の場所)
    private string $publicPath; //Web航海用の相対パス(ブラウザからアクセスする際のURL用)

    public function __construct(
        string $uploadDir = '/var/www/html/uploads',
        string $publicPath = 'uploads/'
    ){
        $this->uploadDir = $uploadDir;
        $this->publicPath = $publicPath;

    }

    /**
     * upload()：アップロード処理
     * PHPでファイルアップロードをすると、$_FILES['input名'] という配列が自動で作られる。
     * それを渡す想定
     */
    public function upload(array $file):?string
    {
        if(empty($file['name'])){
            return null;
        }

        $fileName = basename($file['name']);
        //basename()がパス文字列からファイル名だけを取り出す関数
        //XSS対策で悪意あるパスが来た時に意図しない場所への書き込みを防ぐ。
        $savePath = $this ->uploadDir . '/' .$fileName;
        //$savePathは、ファイル保存先のフルパス

        if (!move_uploaded_file($file['tmp_name'],$savePath)){
            //move_uoloaded_file：HTTPアップロードで受け取った一時ファイルを指定場所へ移動
            throw new RuntimeException("画像の保存に失敗しました:{$fileName}");
        }

        return $this->publicPath . $fileName;
        //Web用の相対パスを返す　例: uploads/photo.jpg
    }
}