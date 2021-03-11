<?php


namespace App\Services\Implementations;


use App\Jobs\ProcessDeposit;
use App\Jobs\ProcessTransfer;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\TransactionsInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TransactionsService implements TransactionsInterface
{

    private $repository;
    private $userRepository;

    public function __construct(TransactionRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $userId
     * @param string|null $accountId
     * @return LengthAwarePaginator|Collection
     */
    public function index(string $userId, string $accountId = null)
    {
        if (!$accountId) {
            $user = $this->userRepository->findById($userId);

            if (!$account = $user->defaultAccount->first()) {
                throw new BadRequestException('Usuário informado não possui uma Conta Default');
            }

            $accountId = $account->id;
        }

        return $this->repository->findByAccount($accountId);
    }

    public function transfer(string $value, string $originAccountId, string $destinationAccountId): Model
    {
        $transaction = $this->repository->store([
            'origin'        => $originAccountId,
            'destination'   => $destinationAccountId,
            'value'         => $value
        ]);
        ProcessTransfer::dispatch($transaction);

        return $transaction;
    }

    public function deposit(string $value, string $accountId): Model
    {
        $transaction = $this->repository->store([
            'destination'   => $accountId,
            'value'         => $value
        ]);
        ProcessDeposit::dispatch($transaction);

        return $transaction;
    }

    public function find(string $id): ?Model
    {
        return $this->repository->findById($id);
    }

}
