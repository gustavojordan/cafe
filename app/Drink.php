<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drink extends MyModel
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

    public function consumerDrinkFavorite()
    {
        return $this->belongsToMany(ConsumerDrinkFavorite::class);
    }
}
