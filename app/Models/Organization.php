<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Organization extends Authenticatable
{
    use HasFactory, HasApiTokens;

    const STATUS_INACTIVE = 10;
    const STATUS_ACTIVE = 200;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'verificate_code',
        'slug',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /**
     * The model's table attributes
     *
     * @var array
     */
    private $tableAttributes = [
        'id',
        'name',
        'email',
        'status'
    ];

    /**
     * The Accessor for the 'status' attribute
     */
    public function getStatusAttribute($value)
    {
        if ($value === self::STATUS_ACTIVE) {
            return __('Active');
        } else if ($value === self::STATUS_INACTIVE) {
            return __('Inactive');
        } else {
            return __('Undefined');
        }
    }

    /**
     * Get attribute names for tables
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
        if ($value === 'Active' || $value === true) {
            return self::STATUS_ACTIVE;
        } else if ($value === 'Inactive' || $value === false) {
            return self::STATUS_INACTIVE;
        }
    }

    /**
     * All statuses are presented in the array
     */
    public static function getClientStatuses()
    {
        return ['Active', 'Inactive', true, false];
    }
}
