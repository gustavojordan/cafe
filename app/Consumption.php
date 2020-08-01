<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    //
    protected $primaryKey = 'consumption_id';
    protected $table = 'consumption';
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
