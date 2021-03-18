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

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CONTA_EMPRESARIAL = 'Empresarial';
    const STATUS_PROCESSADO = 'Processado';
    const STATUS_CANCELADO = 'Cancelado';

    protected $transaction;
    protected $observation;

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
        $origin = $accountService->find($this->transaction->origin);

        if ($origin->type === self::CONTA_EMPRESARIAL) {
            $this->transaction->update([
                'status' => self::STATUS_CANCELADO,
                'observation' => 'Contas Empresariais não podem realizar Transferências!'
            ]);
            return;
        }

        if (!$this->hasAuthorization()){
            $this->transaction->update([
                'status' => self::STATUS_CANCELADO,
                'observation' => $this->observation
            ]);
            return;
        }

        $origin->update([
            'balance' => $origin->balance - $this->transaction->value
        ]);

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

    private function hasAuthorization()
    {
        $response = Http::get(config('transactions.authorization_url'));
        $body = json_decode($response->body());
        if ($body->message !== "Autorizado"){
            $this->observation = $body->message;
            return false;
        }

        return true;
    }
}
