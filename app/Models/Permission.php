<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
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
     * The model's table attributes
     * 
     * @var array
     */
    private $tableAttributes = [
        'id',
        'name',
        'description',
    ];

    /**
     * The users that belong to the permission.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
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
}
