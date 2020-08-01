<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
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

    public function consumption()
    {
        return $this->belongsToMany(Consumer::class, 'consumption', 'consumer_id', 'consumer_id')->withTimestamps()->select('consumption.consumption_id', 'consumption.drink_id', 'consumption.created_at', 'consumption.updated_at');
    }
}
