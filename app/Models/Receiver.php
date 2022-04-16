<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    BankAccount,
    CreditCard
};

class Receiver extends Model
{
    use HasFactory;

    const STATUS_NOT_VERIFIED = 0;
    const STATUS_BLOCKED = 10;
    const STATUS_PROCESSING = 102;
    const STATUS_ACTIVE = 200;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'photo',
        'punch_in',
        'punch_out',
        'if_in_group',
        'verificate_code',
        'password',
        'org_id',
        'status',
        'firebase_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_NOT_VERIFIED,
    ];

    /**
     * The model's table attributes
     *
     * @var array
     */
    private $tableAttributes = [
        'id',
        'first_name',
        'last_name',
        'email',
        'NFC',
        'Organization',
        'Group',
        'status'
    ];

    /**
     * The Accessor for the 'status' attribute
     */
    public function getStatusAttribute($value)
    {
        if ($value === self::STATUS_ACTIVE) {
            return __('Active');
        } else if ($value === self::STATUS_BLOCKED) {
            return __('Blocked');
        } else if ($value === self::STATUS_NOT_VERIFIED) {
            return __('Not verified');
        }
    }

    /**
     * The service provider secondary data
     */
    public function receiverData()
    {
        return $this->hasOne(ReceiverData::class);
    }

    /**
     * Get the receiver's bank account
     */
    public function bankAccount()
    {
        return $this->morphOne(BankAccount::class, 'accountable');
    }

    /**
     * Get the receiver's credit card
     */
    public function creditCard()
    {
        return $this->morphOne(CreditCard::class, 'cardable');
    }

    /**
     * Get attribute names
     */
    public function getAttributeNamesForTable()
    {
        return array_map('ucfirst', preg_replace('#[_]+#', ' ', $this->tableAttributes));
    }

    /**
     * Extract necessary object attributes for the tables
     */
    public function getAttributesForTable()
    {
        $attributes = [];
        foreach ($this->attributesToArray() as $key => $value) {
            if (in_array($key, $this->tableAttributes)) {
                $attributes[$key] = $value;
            }
        }
        return $attributes;
    }

    /**
     * Set the status of a saved model
     * @warning It mustn't be 'setStatusAttribute' 'cause Laravel will process it through the core
     * in that case
     */
    public static function setStatus($value)
    {
        if ($value === 'Active') {
            return self::STATUS_ACTIVE;
        } else if ($value === 'Blocked') {
            return self::STATUS_BLOCKED;
        } else if ($value === 'Not verified') {
            return self::STATUS_NOT_VERIFIED;
        }
    }

    public function getKYCStatusAttribute($value)
    {
        if ($value === self::STATUS_ACTIVE) {
            return __('Active');
        } else if ($value === self::STATUS_BLOCKED) {
            return __('Rejected');
        } else if ($value === self::STATUS_PROCESSING) {
            return __('Processing');
        } else if ($value === self::STATUS_NOT_VERIFIED) {
            return __('Error');
        }
    }

    /**
     * All statuses are presented in the array
     */
    public static function getClientStatuses()
    {
        return ['Active', 'Blocked', 'Not verified'];
    }
}
