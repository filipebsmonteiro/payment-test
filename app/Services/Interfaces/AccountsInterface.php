<?php


namespace App\Services\Interfaces;


use Illuminate\Database\Eloquent\Model;

interface AccountsInterface
{
    public function list();

    public function store(string $type, string $userId, bool $isDefault): ?Model;

    public function find(string $id): ?Model;

    public function updateBalance(string $id, string $balance): ?Model;

    public function getDefaultAccountByUserId(string $userId): ?Model;
}
