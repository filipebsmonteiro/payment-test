<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\Interfaces\UsersInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = resolve(UsersInterface::class);
    }

    public function index()
    {
        return $this->service->list();
    }

    public function store(UserRequest $request): ?Model
    {
        $attributes = $request->validated();
        $attributes['password'] = bcrypt($request->password);
        return $this->service->store($attributes);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update(UserRequest $request, $id)
    {
        $attributes = $request->validated();
        if ($request->has('password')) {
            $attributes['password'] = bcrypt($request->password);
        }

        return $this->service->update($id, $attributes);
    }

    public function destroy($id)
    {
        // TODO: Implement method
    }
}
