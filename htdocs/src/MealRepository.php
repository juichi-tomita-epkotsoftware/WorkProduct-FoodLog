<?php
namespace App\Repository;

use PDOException;

Class MealRepository implements MealRepositoryInterface
//DB操作クラス(SQL担当)

{
    private \PDO $dbh;
    public  function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**一覧取得（論理削除されていないもの） */
    public  function getActive():array
    {
        $stmt = $this->dbh->query('SELECT * FROM meals WHERE is_deleted IS NULL ORDER BY meal_date DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public  function getSearched(string $keyword):array|null
    //クエリ内では変数の直接の埋め込みは不可
    {
        $stmt = $this->dbh->prepare('SELECT * FROM meals WHERE meal_name LIKE :keyword AND is_deleted IS NULL');
        $stmt->bindValue(':keyword', '%' . $keyword . '%', \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**論理消去済み一覧 */
    public  function    getDeleted():array
    {
        $stmt = $this->dbh->query('SELECT * FROM meals WHERE is_deleted IS TRUE');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**新規登録 */
    public function create(array $data):void
    {
        $sql = 'INSERT INTO meals(meal_name,meal_date,meal_time,image_path,comment,cook_time,food_cost)
                VALUES(:name,CURDATE(),:time,:image,:comment,:cook_time,:food_cost)';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name',   $data['name'],  \PDO::PARAM_STR);
        $stmt->bindValue(':time',  $data['time'],  \PDO::PARAM_STR);
        $stmt->bindValue(':image',  $data['image'],  \PDO::PARAM_STR);
        $stmt->bindValue(':comment',  $data['comment'],  \PDO::PARAM_STR);
        $stmt->bindValue(':cook_time',  $data['cook_time'],  \PDO::PARAM_STR);
        $stmt->bindValue(':food_cost',  $data['food_cost'],  \PDO::PARAM_STR);
        $stmt->execute();
    }

    /**論理削除 */
    public function softDelete(int $id): void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET is_deleted = NOW() WHERE id = :id');
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();
    }

    /**物理削除 */
    public  function hardDelete(int $id): void
    {
        $stmt = $this->dbh->prepare('DELETE FROM meals WHERE id = :id');
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();
    }

    /**復元 */
    public function restore(int $id):void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET is_deleted = NULL WHERE id =:id');
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateSortOrder(int $id,int $sortOrder):void
    {
        $stmt = $this->dbh->prepare('UPDATE meals SET sort_order = :sort WHERE id = :id');
        $stmt->bindValue(':sort',$sortOrder,\PDO::PARAM_INT);
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findById(int $id):?array
    {
        $stmt = $this->dbh->prepare('SELECT * FROM meals WHERE id = :id');
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        }

    public function findByDateRange(string $from,string $to):array|null{
        if(isset($from)&& $to);
        try{
            $stmt = $this->dbh->prepare(
                'SELECT * FROM meals
                 WHERE meal_date >= :from AND meal_date < :to
                 AND is_deleted IS NULL
                 ORDER BY meal_date DESC');
            $stmt->bindValue(':from',$from,\PDO::PARAM_INT);
            $stmt->bindValue(':to',$to,\PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: null;
        }catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }

    public function edit(array $data,int $id)
    {
        if(isset($data['name'])&& $data['time'] && $data['comment'] && $data['comment'] && $data['cook_time'] && $data['food_cost']);
        try{
            $stmt = $this->dbh->prepare('UPDATE meals SET meal_name = :meal_name, meal_time = :meal_time, comment = :comment,cook_time = :cook_time,food_cost = :food_cost WHERE id=:id');
            $stmt->bindValue(':meal_name',   $data['name'],  \PDO::PARAM_STR);
            $stmt->bindValue(':meal_time',  $data['time'],  \PDO::PARAM_INT);
            $stmt->bindValue(':comment',  $data['comment'],  \PDO::PARAM_STR);
            $stmt->bindValue(':cook_time',  $data['cook_time'],  \PDO::PARAM_INT);
            $stmt->bindValue(':food_cost',  $data['food_cost'],  \PDO::PARAM_STR);
            $stmt->bindValue(':id',  $id,  \PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }
}



