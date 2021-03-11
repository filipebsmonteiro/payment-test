<?php


namespace App\Services\Interfaces;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TransactionsInterface
{
    /**
     * @param string $userId
     * @param string|null $accountId
     * @return LengthAwarePaginator|Collection
     */
    public function index(string $userId, string $accountId = null);

    public function find(string $id): ?Model;

    public function transfer(string $value, string $originAccountId, string $destinationAccountId): Model;

    public function deposit(string $value, string $accountId): Model;
}
