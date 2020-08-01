<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $primaryKey = 'drink_id';
    protected $table = 'drink';

    protected $fillable = [
        'name', 'description', 'caffeine', 'slug'
    ];

    public function consumption()
    {
        return $this->belongsToMany(Consumption::class);
    }
}
