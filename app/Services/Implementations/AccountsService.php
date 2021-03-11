<?php


namespace App\Services\Implementations;


use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\AccountsInterface;
use App\Services\Interfaces\UsersInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AccountsService implements AccountsInterface
{
    private $repository;
    private $userRepository;

    public function __construct(AccountRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Collection|LengthAwarePaginator
     */
    public function list()
    {
        return $this->repository->findByRequestFilters();
    }

    public function store(string $type, string $userId, bool $isDefault): ?Model
    {
        $userService = resolve(UsersInterface::class);
        $user = $userService->find($userId);

        if ($user->accounts->where('type', $type)->count() > 0) {
            throw new BadRequestException('Conta deste tipo já existe para esse usuário');
        }

        $account = $this->repository->store([
            'type' => $type,
            'is_default' => $isDefault
        ]);
        $user->accounts()->attach($account->id);

        return $account;
    }

    public function find(string $id): ?Model
    {
        return $this->repository->findById($id);
    }

    public function updateBalance(string $id, string $balance): ?Model
    {
        return $this->repository->update(['balance' => $balance], $id);
    }

    public function getDefaultAccountByUserId(string $userId): ?Model
    {
        if (!$user = $this->userRepository->findById($userId)) {
            throw new BadRequestException("Usuário ID: $userId informado não existe");
        }
        if (!$account = $user->defaultAccount->first()) {
            throw new BadRequestException("Usuário ID: $userId não possui uma Conta Default");
        }
        return $account;
    }
}
