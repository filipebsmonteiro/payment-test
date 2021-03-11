<?php


namespace App\Repositories;


use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository extends Repository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findByAccount(string $accountId)
    {
        $this->Query = $this->Query
            ->orWhere('origin', $accountId)
            ->orWhere('destination', $accountId)
            ->orderBy('created_at', 'DESC');

        $this->executeQuery();

        return $this->Results;
    }
}
