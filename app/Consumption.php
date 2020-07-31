<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    //
    protected $primaryKey = 'consumption_id';
    protected $table = 'consumption';

    public function drink()
    {
        return $this->belongsTo(Consumer::class);
    }
    
}
