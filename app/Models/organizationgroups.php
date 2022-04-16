<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organizationgroups extends Model
{
    use HasFactory;
    protected $table = 'organizationgroups';

    protected $fillable = [
        'org_id',
        'name',
        'users',
    ];

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
    'users' => 'array',
    ];

    private $tableAttributes = [
        'id',
        'name',
        'users',
        'date',
        'time'
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
