<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'status',
        'observation',
        'origin',
        'destination'
    ];

    public function originAccount()
    {
        return $this->hasOne(Account::class, 'id', 'origin');
    }

    public function destinationAccount()
    {
        return $this->hasOne(Account::class, 'id', 'destination');
    }
}
