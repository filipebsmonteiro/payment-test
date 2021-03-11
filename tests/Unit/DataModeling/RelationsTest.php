<?php

namespace Unit\DataModeling;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use DatabaseTransactions;

    private $user, $firstAccount, $secondAccount, $thirdAccount;
    private $transaction, $origin, $destination;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->firstAccount = Account::factory()->create(['is_default' => true]);
        $this->secondAccount = Account::factory()->create();
        $this->thirdAccount = Account::factory()->create();
        $this->user->accounts()->attach([$this->firstAccount->id, $this->secondAccount->id, $this->thirdAccount->id]);

        $this->transaction = Transaction::create([
            'value' => 15,
            'origin' => 1,
            'destination' => 2
        ]);
        $this->origin = Account::findOrFail(1);
        $this->destination = Account::findOrFail(2);
    }

    public function test_accounts_belongs_to_user()
    {
        $this->assertEquals(3, $this->user->accounts->count());

        $this->assertTrue($this->user->accounts->contains($this->firstAccount));
        $this->assertTrue($this->user->accounts->contains($this->secondAccount));
        $this->assertTrue($this->user->accounts->contains($this->thirdAccount));
    }

    public function test_user_has_default_account()
    {
        $this->assertInstanceOf(Account::class, $this->user->defaultAccount->first());

        $this->assertTrue($this->user->defaultAccount->contains($this->firstAccount));

        $this->assertEquals($this->firstAccount->id, $this->user->defaultAccount->first()->id);
        $this->assertEquals($this->firstAccount->type, $this->user->defaultAccount->first()->type);
        $this->assertEquals($this->firstAccount->is_default, $this->user->defaultAccount->first()->is_default);

        $this->assertEquals(1, $this->user->defaultAccount->count());
    }

    public function test_transfer_origin_account()
    {
        $this->assertEquals($this->transaction->originAccount->id, $this->origin->id);
        $this->assertEquals($this->transaction->originAccount->type, $this->origin->type);
        $this->assertEquals($this->transaction->originAccount->is_default, $this->origin->is_default);
    }

    public function test_transfer_destination_account()
    {
        $this->assertEquals($this->transaction->destinationAccount->id, $this->destination->id);
        $this->assertEquals($this->transaction->destinationAccount->type, $this->destination->type);
        $this->assertEquals($this->transaction->destinationAccount->is_default, $this->destination->is_default);
    }
}
