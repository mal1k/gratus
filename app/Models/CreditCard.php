<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cardable_id',
        'cardable_type',
        'number',
        'expired_date',
        'secret_code',
        'zip_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cardable_type',
        'cardable_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the parent cardable model (tipper or receiver)
     */
    public function cardable()
    {
        return $this->morphTo();
    }
}
