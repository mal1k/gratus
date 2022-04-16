<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
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
        'transaction_id',
        'org_id',
        'receiver_id',
        'tipper_id',
        'amount',
        'stars',
        'comment',
        'status',
        'anon_transfer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
        'transaction_id',
        'tipper',
        'receiver',
        'organization',
        'status',
        'amount',
        'date',
        'time'
    ];

    /**
     * The Accessor for the 'status' attribute
     */
    public function getStatusAttribute($value)
    {
        if ($value === self::STATUS_ACTIVE) {
            return __('Approved');
        } else if ($value === self::STATUS_BLOCKED) {
            return __('Rejected');
        } else if ($value === self::STATUS_PROCESSING) {
            return __('Processing');
        } else if ($value === self::STATUS_NOT_VERIFIED) {
            return __('Error');
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
        foreach ($this->attributesToArray() as $key => $value) {
            if (in_array($key, $this->tableAttributes)) {
                $attributes[$key] = $value;
            }
        }
        return $attributes;
    }
}
