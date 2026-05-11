<?php
namespace App\Repository;
interface MealRepositoryInterface
{
    public function getActive(): array;
    public function getDeleted(): array;
    public function create(array $data): void;
    public function softDelete(int $id): void;
    public function hardDelete(int $id): void;
    public function restore(int $id): void;
    public function updateSortOrder(int $id,int $sortOrder): void;
    public function findById(int $id): ?array; //?arrayは戻り値はarrayかnullのどちらかを返すという宣言
}