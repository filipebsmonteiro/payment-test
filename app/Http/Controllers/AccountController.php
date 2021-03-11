<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Services\Interfaces\AccountsInterface;

class AccountController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = resolve(AccountsInterface::class);
        $this->middleware('auth:api', ['only' => ['store']]);
    }

    public function index()
    {
        $this->setUserIdOnRequest();
        $this->request->filters = [['user_id', '=', $this->request->user_id]];

        return $this->service->list();
    }

    public function store(AccountRequest $request)
    {
        $this->setUserIdOnRequest();
        return $this->service->store($request->type, $request->user_id, false);
    }

    public function show(int $id)
    {
        return $this->service->find($id);
    }
}
