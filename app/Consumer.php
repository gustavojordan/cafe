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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consumption()
    {
        return $this->hasMany(Consumption::class);
    }
}
