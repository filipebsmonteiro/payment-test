<?php


namespace App\Services\Implementations;


use App\Events\UserCreated;
use App\Repositories\UserRepository;
use App\Services\Interfaces\UsersInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UsersService implements UsersInterface
{
    const DIGITAL_ACCOUNT = 'Digital';
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(array $filters = [])
    {
        if (!empty($filters)) {
            $this->repository->setRequestFilters($filters);
        }

        return $this->repository->findByRequestFilters();
    }

    public function store(array $attributes): Model
    {
        $user = $this->repository->store($attributes);
        UserCreated::dispatch(self::DIGITAL_ACCOUNT, $user->id);
        return $user;
    }

    public function find(string $id): ?Model
    {
        return $this->repository->findById($id);
    }

    public function update(string $id, array $fields): ?Model
    {
        return $this->repository->update($fields, $id);
    }

    public function delete(string $id): bool
    {
        return $this->repository->destroy($id);
    }

    public function authUser(): ?Authenticatable
    {
        return $this->repository->getAuthUser();
    }
}
