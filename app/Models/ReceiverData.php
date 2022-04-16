<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiverData extends Model
{
    use HasFactory;

    const KYC_IS_NOT_PASSED = false;
    const KYC_IS_PASSED = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receiver_id',
        'is_kyc_passed',
        'phone_number',
        'ssn',
        'birth_date',
        'address',
        'address_2',
        'postal_code',
        'city',
        'state',
        'country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'receiver_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The Accessor for the 'is_kyc_passed' attribute
     */
    public function getIsKycPassedAttribute($value)
    {
        if ($value) {
            return self::KYC_IS_PASSED;
        }

        return self::KYC_IS_NOT_PASSED;
    }

    /**
     * The owner receiver of the data
     */
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
}
