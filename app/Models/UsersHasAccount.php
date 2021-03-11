<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersHasAccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_id'
    ];
}
