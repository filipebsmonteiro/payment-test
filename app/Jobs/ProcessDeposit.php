<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\Interfaces\AccountsInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessDeposit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const STATUS_PROCESSADO = 'Processado';
    const STATUS_CANCELADO = 'Cancelado';

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accountService = resolve(AccountsInterface::class);
        $destination = $accountService->find($this->transaction->destination);

        $destination->update([
            'balance' => $destination->balance + $this->transaction->value
        ]);

        $this->transaction->update([
            'status' => self::STATUS_PROCESSADO
        ]);

        dispatch(new NotifyTransaction($this->transaction));
    }
}
