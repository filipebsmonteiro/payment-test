<?php

namespace Unit\DataModeling;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UsersHasAccount;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RepositoriesTest extends TestCase
{
    use DatabaseTransactions;

    private $user, $userRepository,
        $accounts, $accountRepository,
        $firstTransaction, $secondTransaction, $transactionRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->userRepository = new UserRepository($this->user);

        $this->accounts = Account::factory()->count(2)->create();
        $this->accountRepository = new AccountRepository($this->accounts->first());

        $this->firstTransaction = Transaction::factory()->create([
            'origin' => $this->accounts->first()->id,
            'destination' => $this->accounts->last()->id
        ]);
        $this->secondTransaction = Transaction::factory()->create([
            'origin' => $this->accounts->last()->id,
            'destination' => $this->accounts->first()->id
        ]);
        $this->transactionRepository = new TransactionRepository($this->firstTransaction);

    }

    public function test_filter_user()
    {
        $results = $this->userRepository->findByFilters([['name', 'LIKE', $this->user->name]]);
        $this->assertTrue($results->contains($this->user));

        $results = $this->userRepository->findByFilters([['email', 'LIKE', $this->user->email]]);
        $this->assertTrue($results->contains($this->user));
    }

    public function test_filter_accounts_by_user_id()
    {
        foreach ($this->accounts as $account) {
            UsersHasAccount::create(['user_id' => $this->user->id, 'account_id' => $account->id]);
        }

        $results = $this->accountRepository->findByRequestFilters([['user_id', '=', $this->user->id]]);

        $this->assertEquals(2, $results->where('user_id', $this->user->id)->count());
    }

    public function test_filter_transcations_by_account_id()
    {
        $transactions = $this->transactionRepository->findByAccount($this->accounts->first()->id);
        $this->assertTrue($transactions->contains($this->firstTransaction));
        $this->assertTrue($transactions->contains($this->secondTransaction));
    }
}
