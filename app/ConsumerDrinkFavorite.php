<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumerDrinkFavorite extends MyModel
{
    protected $primaryKey = 'consumer_drink_favorite_id';
    protected $table = 'consumer_drink_favorite';
    protected $fillable = [
        'drink_id', 'consumer_id'
    ];


    public function drink()
    {
        return $this->belongsToMany(Drink::class);
    }

    public function consumer()
    {
        return $this->belongsToMany(Consumer::class);
    }
}
