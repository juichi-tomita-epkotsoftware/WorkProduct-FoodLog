<?php

Class MealRepository
//DB操作クラス(SQL担当)
{
    private PDO $dbh;

    //コンストラクタでDBを受け取る
    public  function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**一覧取得（論理削除されていないもの） */
    public  function getActive():array
    {
        $stmt = $this->dbh->query('SELECT * FROM meals WHERE is_deleted IS NULL');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**論理消去済み一覧 */
    public  function    getDeleted():array
    {
        $stmt = $this->dbh->query('SELECT * FROM meals WHERE is_deleted IS TRUE');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**新規登録 */
    public function create(array $data):void
    {
        $sql = 'INSERT INTO meals(meal_name,meal_date,meal_time,image_path,comment,cook_time,food_cost)
                VALUES(:name,CURDATE(),:time,:image,:comment,:cook_time,:food_cost)';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name',   $data['name'],  PDO::PARAM_STR);
        $stmt->bindValue(':time',  $data['time'],  PDO::PARAM_STR);
        $stmt->bindValue(':image',  $data['image'],  PDO::PARAM_STR);
        $stmt->bindValue(':comment',  $data['comment'],  PDO::PARAM_STR);
        $stmt->bindValue(':cook_time',  $data['cook_time'],  PDO::PARAM_STR);
        $stmt->bindValue(':food_cost',  $data['food_cost'],  PDO::PARAM_STR);
        //public functionの間はスペースが正解かタブが正解か
        //なぜ第一引数だけシングルクオテーションで囲っているか
        //それぞれ引数の役割は何なのか
        $stmt->execute();
    }

    /**論理削除 */
    public function softDelete(int $id): void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET is_deleted = NOW() WHERE id = :id');
        //:idて何
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    }

    /**物理削除 */
    public  function hardDelete(int $id): void
    {
        $stmt = $this->dbh->prepare('DELETE FROM meals WHERE id = :id');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    }

    /**復元 */
    public function restore(int $id):void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET is_deleted = NULL WHERE id =:id');
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateSortOrder(int $id,int $sortOrder):void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET sort_order = :sort WHERE id = :id');
        $stmt->bindValue(':sort',$sortOrder,PDO::PARAM_INT);
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    }

    /** */
    public function findById(int $id): ?array
    {
        $stmt = $this->dbh->prepare('SELECT * FROM meals WHERE id = :id');
        $stmt ->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt ->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; //見つからなければnullを返す
    }
}



