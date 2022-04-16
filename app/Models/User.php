<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const STATUS_PENDING = 10;
    const STATUS_APPROVED = 200;

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
        'verificate_code',
        'password',
        'verificate_code',
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
        'status' => self::STATUS_PENDING,
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
        'status'
    ];

    /**
     * The Accessor for the 'status' attribute
     */
    public function getStatusAttribute($value)
    {
        if ($value === self::STATUS_APPROVED) {
            return __('Active');
        } else if ($value === self::STATUS_PENDING) {
            return __('Pending');
        } else {
            return __('Undefined');
        }
    }

    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
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
        if ($value === 'Active' || $value === true) {
            return self::STATUS_APPROVED;
        } else if ($value === 'Pending' || $value === false) {
            return self::STATUS_PENDING;
        }
    }

    /**
     * All statuses are presented in the array
     */
    public static function getClientStatuses()
    {
        return ['Active', 'Pending', true, false];
    }
}
