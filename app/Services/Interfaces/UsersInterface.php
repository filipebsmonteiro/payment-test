<?php


namespace App\Services\Interfaces;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface UsersInterface
{
    public function list(array $filters = []);

    public function store(array $attributes): ?Model;

    public function find(string $id): ?Model;

    public function update(string $id, array $fields): ?Model;

    public function delete(string $id): bool;

    public function authUser(): ?Authenticatable;
}
