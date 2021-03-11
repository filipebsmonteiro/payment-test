<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'balance',
        'is_default'
    ];

    public function user()
    {
        $this->belongsToMany(
            User::class,
            UsersHasAccount::class,
            'user_id',
            'account_id'
        );
    }
}
