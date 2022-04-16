<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'model',
        'user_id',
        'punch_in',
        'punch_out',
        'token'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    private $tableAttributes = [
        'id',
        'fullname',
        'organization',
        'model',
        'punch_in',
        'punch_out'
    ];

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
