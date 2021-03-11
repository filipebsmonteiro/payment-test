<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\Interfaces\AccountsInterface;
use App\Services\Interfaces\TransactionsInterface;
use App\Services\Interfaces\UsersInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TransactionController extends Controller
{
    protected $service;
    protected $accountService;
    protected $userService;

    public function __construct()
    {
        $this->service = resolve(TransactionsInterface::class);
        $this->accountService = resolve(AccountsInterface::class);
        $this->userService = resolve(UsersInterface::class);
    }

    public function index()
    {
        $this->setUserIdOnRequest();
        return $this->service->index($this->request->user_id);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function transfer(TransactionRequest $request)
    {
        $originAccount = $this->getOriginAccount($request);
        $destinationAccount = $this->getDestinationAccount($request);
        return $this->service->transfer($request->value, $originAccount->id, $destinationAccount->id);
    }

    public function deposit(TransactionRequest $request)
    {
        $destinationAccount = $this->getDestinationAccount($request);
        return $this->service->deposit($request->value, $destinationAccount->id);
    }

    protected function getOriginAccount(Request $request): Model
    {
        $this->userService = resolve(UsersInterface::class);
        $this->accountService = resolve(AccountsInterface::class);

        if ($request->has('origin_account_id')) {
            $originAccount = $this->accountService->find($request->origin_account_id);
        }

        if (!isset($originAccount) && $request->has('origin_email')) {
            $users = $this->userService->list([['email', '=', $request->origin_email]]);
            if (!$user = $users->first()){
                throw new BadRequestException('Nenhum usuário encontrado para o email de origem');
            }

            $originAccount = $this->accountService->getDefaultAccountByUserId($user->id);
        }

        if (!isset($originAccount) && $request->has('origin_document')) {
            $users = $this->userService->list([['document', '=', $request->origin_document]]);
            if (!$user = $users->first()){
                throw new BadRequestException('Nenhum usuário encontrado para o documento de origem');
            }

            $originAccount = $this->accountService->getDefaultAccountByUserId($user->id);
        }

        return $originAccount;
    }

    protected function getDestinationAccount(Request $request): Model
    {
        $this->userService = resolve(UsersInterface::class);
        $this->accountService = resolve(AccountsInterface::class);

        if ($request->has('destination_account_id')) {
            $destinationAccount = $this->accountService->find($request->destination_account_id);
        }

        if ($request->has('destination_email')) {
            $users = $this->userService->list([['email', '=', $request->destination_email]]);
            if (!$user = $users->first()){
                throw new BadRequestException('Nenhum usuário encontrado para o email de destino');
            }

            $destinationAccount = $this->accountService->getDefaultAccountByUserId($user->id);
        }

        if ($request->has('destination_document')) {
            $users = $this->userService->list([['document', '=', $request->destination_document]]);
            if (!$user = $users->first()){
                throw new BadRequestException('Nenhum usuário encontrado para o documento de destino');
            }

            $destinationAccount = $this->accountService->getDefaultAccountByUserId($user->id);
        }

        return $destinationAccount;
    }
}
