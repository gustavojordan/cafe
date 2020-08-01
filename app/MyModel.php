<?php

namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class MyModel extends EloquentModel
{
    public function beginTransaction()
    {
        self::getConnectionResolver()->connection()->beginTransaction();
    }

    public function commit()
    {
        self::getConnectionResolver()->connection()->commit();
    }

    public  function rollBack()
    {
        self::getConnectionResolver()->connection()->rollBack();
    }
}
