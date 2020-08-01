<?php

namespace App;

class Consumer extends MyModel
{

    protected $primaryKey = 'consumer_id';
    protected $table = 'consumer';
    protected $fillable = [
        'user_id', 'consumption_limit'
    ];
    protected $hidden = array('pivot');

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favoriteDrinks()
    {
        return $this->hasMany(ConsumerDrinkFavorite::class, 'consumer_id');
    }

    public function saveDrinkFavorite()
    {
        return $this->belongsToMany(ConsumerDrinkFavorite::class, 'consumer_drink_favorite', 'consumer_id', 'drink_id')->withTimestamps();
    }

    public function consumption()
    {
        return $this->belongsToMany(Consumer::class, 'consumption', 'consumer_id', 'consumer_id')->withTimestamps()->select('consumption.consumption_id', 'consumption.drink_id', 'consumption.created_at', 'consumption.updated_at');
    }
}
