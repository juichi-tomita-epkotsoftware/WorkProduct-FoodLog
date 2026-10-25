<?php

use App\Src\Logger;
use App\Repository\MealRepository;

class MealController
{
    public function __construct(
        private MealRepository $repo,
        private ImageUploader $uploder
    ) {}
    public function handleRequest(array $post, array $files)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($post['inputName']) && !isset($post['edit_id'])) {
                $errors = $this->validate($post);
                if (!empty($errors)) {
                    return $errors;
                }
                $this->create($post, $files);
                return;
            }
            if (isset($post['edit_id'])) {
                $this->edit($post, $files);
                return;
            }
            if (isset($post['sort_order'])) {
                $this->updateSortOrder($post);
                return;
            }
            if (isset($post['delete_id'])) {
                $this->delete($post);
                return;
            }
            if (isset($post['restore_id'])) {
                $this->restore($post);
                return;
            }
        } elseif (isset($_GET['from'], $_GET['to'])) {
            $this->findbydaterang();
        }
    }

    //画像アップロード+食事データの登録+ログ記録
    private function create(array $post, array $files): void
    {
        $imagePath = $this->uploder->upload($files['inputImage']);

        //ログ記録のif文
        if ($imagePath !== null) {
            Logger::write('IMAGE', "アップロード成功{$imagePath}");
        } else {
            Logger::write('IMAGE', '画像なし(テキストのみ登録)');
        }

        //食事データの登録
        $this->repo->create([
            'name'  => $post['inputName'],
            'time'  => $post['inputTime'],
            'image'  => $imagePath,
            'comment'  => $post['inputComment'] ?? '',
            'cook_time'  => $post['inputMakingTime'] ?? '',
            'food_cost'  => $post['inputMakingCost'] ?? '0',
        ]);
        Logger::write('CREATE', "食事登録: name={$post['inputName']}");
    }

    private function validate(array $post): array
    {
        $errors = [];

        if ($post['inputName'] === '') {
            $errors['inputName'] = '料理名は必須です';
        } elseif (mb_strlen($post['inputName']) > 50) {
            $errors['inputName'] = '料理名は50を超えないで下さい';
        }

        if (!ctype_digit($post['inputMakingTime'])) {
            $errors['inputMakingTime'] = '調理時間は0以上の整数にしてください';
        }

        if (!ctype_digit($post['inputMakingCost'])) {
            $errors['inputMakingCost'] = '調理費用は0以上の整数にしてください';
        }
        return $errors;
    }

    //並び順更新
    private function updateSortOrder(array $post): void
    {
        $id = (int)$post['id'];
        $sortOrder = (int)$post['sort_order'];

        $this->repo->updateSortOrder($id, $sortOrder);
        Logger::write('UPDATE', "並び順更新:id={$id},sort_order={$sortOrder}");
    }

    //消去(論理 or 物理)
    private function delete(array $post): void
    {
        $id = (int)$post['delete_id'];

        if ($post['delete_type'] === 'logical') {
            $this->repo->softDelete($id);
            Logger::write('DELETE', "論理削除:id={$id}");
        } elseif ($post['delete_type'] === 'physical') {
            $this->repo->hardDelete($id);
            Logger::write('DELETE', "物理削除:id={$id}");
        }
    }

    //復元
    private function restore(array $post): void
    {
        $id = (int)$post['restore_id'];
        $this->repo->restore($id);
        Logger::write('RESTORE', "復元:id={$id}");
    }

    //編集
    private function edit(array $post, array $files): void
    {
        $id = (int)$post['edit_id'];
        $imagePath = $this->uploder->upload($files['inputImage']);

        $this->repo->edit([
            'name'  => $post['inputName'],
            'time'  => $post['inputTime'],
            'comment'  => $post['inputComment'] ?? '',
            'cook_time'  => $post['inputMakingTime'] ?? '',
            'food_cost'  => $post['inputMakingCost'] ?? '0',
        ], $id);
        Logger::write('RESTORE', "編集:id={$id}");
    }

    private function findbydaterang(): void
    {
        $from = $_GET['from'];
        $to = $_GET['to'];

        $this->repo->findByDateRange($from, $to);
        Logger::write('DATESERCH', "{$from}から{$to}のデータ取得");
    }
}
