<?php
use App\Src\Logger;
use App\Repository\MealRepository;

Class MealController
//リクエストを適切な関数へルーティング
{
    //コンストラクタ    $repoと$uploderを自身の関数として使えるようにしておく。
    public function __construct(
        private MealRepository $repo,
        private ImageUploader $uploder
    ){}

    //index.phpが唯一参照する窓口
    public function handleRequest(array $post,array $files):void{
        if(!empty($post['inputName']))  $this->create($post,$files);
        if(isset($post['sort_order']))  $this->updateSortOrder($post);
        if(isset($post['delete_id']))   $this->delete($post);
        if(isset($post['restore_id']))   $this->restore($post);
    }
    //:void この関数は何も返さないという宣言
    //画像アップロード+食事データの登録+ログ記録
    private function create(array $post,array $files):void
    {
        //画像登録
        $imagePath = $this->uploder->upload($files['inputImage']);

        //ログ記録のif文
        if ($imagePath !== null){
            Logger::write('IMAGE',"アップロード成功{$imagePath}");
        } else{
            Logger::write('IMAGE','画像なし(テキストのみ登録)');
        }
        //食事データの登録
        $this->repo->create([
            'name'  =>$post['inputName'],
            'time'  =>$post['inputTime'],
            'image'  =>$imagePath,
            'comment'  =>$post['inputComment'] ?? '',
            'cook_time'  =>$post['inputMakingTime'] ?? '',
            'food_cost'  =>$post['inputMakingCost'] ?? '0',
        ]);
        Logger::write('CREATE',"食事登録: name={$post['inputName']}");
        }

    //並び順更新
    private function updateSortOrder(array $post):void
    {
        $id =(int)$post['id'];
        $sortOrder =(int)$post['sort_order'];

        $this->repo->updateSortOrder($id,$sortOrder);
        Logger::write('UPDATE',"並び順更新:id={$id},sort_order={$sortOrder}");
    }

    //消去(論理 or 物理)
    private function delete(array $post) : void
     {
        $id = (int)$post['delete_id'];

        if($post['delete_type'] === 'logical'){
            $this->repo->softDelete($id);
            Logger::write('DELETE',"論理削除:id={$id}");
        }elseif($post['delete_type'] === 'physical'){
            $this->repo->hardDelete($id);
            Logger::write('DELETE',"物理削除:id={$id}");
        }
    }

    //復元
    private function restore(array $post):void
    {
        $id = (int)$post['restore_id'];
        $this->repo->restore($id);
        Logger::write('RESTORE',"復元:id={$id}");
    }
}
