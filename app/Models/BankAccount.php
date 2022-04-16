<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'bank_code',
        'account_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'accountable_type',
        'accountable_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the parent cardable model (tipper or receiver)
     */
    public function accountable()
    {
        return $this->morphTo();
    }
}
