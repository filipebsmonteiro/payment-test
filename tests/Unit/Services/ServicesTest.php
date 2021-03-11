<?php

namespace Unit\Services;

use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\Interfaces\TransactionsInterface;
use App\Services\Interfaces\UsersInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use DatabaseTransactions;

    const CONTA_EMPRESARIAL = 'Empresarial';
    const STATUS_CANCELADO = 'Cancelado';

    private $user, $accountMock;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->make()->toArray();
        $user['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        $userService = resolve(UsersInterface::class);
        $this->user = $userService->store($user);

        $this->accountMock = Account::factory()->make();
    }

    public function test_first_account_on_create_user()
    {
        $accountRepo = new AccountRepository($this->accountMock);
        $results = $accountRepo->findByRequestFilters([['user_id', '=', $this->user->id]]);

        $this->assertEquals(1, $results->where('user_id', $this->user->id)->count());
    }

    public function test_list_default_account()
    {
        $account = Account::factory()->make();
        $this->accountRepository = new AccountRepository($account);

        $results = $this->accountRepository->findByRequestFilters([['user_id', '=', $this->user->id]]);

        $this->assertEquals(1, $results->where('user_id', $this->user->id)->count());
        $this->assertEquals(true, $results->first()->is_default);
    }

    public function test_transfer()
    {
        $value = 15;
        $accountRepo = new AccountRepository($this->accountMock);
        $beforeOriginBalance = $accountRepo->findById(1)->balance;

        $accountRepo = new AccountRepository($this->accountMock);
        $beforeDestinationBalance = $accountRepo->findById(2)->balance;

        $transactionService = resolve(TransactionsInterface::class);
        $transactionService->transfer($value, '1', '2');

        $accountRepo = new AccountRepository($this->accountMock);
        $afterOriginBalance = $accountRepo->findById(1)->balance;

        $accountRepo = new AccountRepository($this->accountMock);
        $afterDestinationBalance = $accountRepo->findById(2)->balance;

        $this->assertEquals($beforeOriginBalance - $value, $afterOriginBalance);
        $this->assertEquals($beforeDestinationBalance + $value, $afterDestinationBalance);
    }

    public function test_deposit()
    {
        $value = 15;
        $accountRepo = new AccountRepository($this->accountMock);
        $beforeDestinationBalance = $accountRepo->findById(1)->balance;

        $transactionService = resolve(TransactionsInterface::class);
        $transactionService->deposit($value, '1');

        $accountRepo = new AccountRepository($this->accountMock);
        $afterDestinationBalance = $accountRepo->findById(1)->balance;

        $this->assertEquals($beforeDestinationBalance + $value, $afterDestinationBalance);
    }

    public function test_company_transfer()
    {
        $account = Account::factory()->create(['type' => self::CONTA_EMPRESARIAL]);

        $transactionService = resolve(TransactionsInterface::class);
        $transaction = $transactionService->transfer(50, (string)$account->id, '1');

        $transactionRepository = new TransactionRepository($transaction);
        $created = $transactionRepository->findById($transaction->id);

        $this->assertEquals(self::STATUS_CANCELADO, $created->status);
    }
}
