<?php

namespace App;


class Consumer extends MyModel
{

    protected $appends = ['links'];

    protected $primaryKey = 'consumer_id';
    protected $table = 'consumer';
    protected $fillable = [
        'user_id', 'consumption_limit'
    ];
    protected $hidden = array('pivot');


    public function getLinksAttribute()
    {
        return [
            [
                'href' => route('consumers.consumers.show', ['consumer' => $this->consumer_id]),
                'rel' => 'self'
            ],
            [
                'href' => route('consumers.consumers.consume', ['consumer_id' => $this->consumer_id]),
                'rel' => 'consume'
            ],
            [
                'href' => route('consumers.consumers.consumption', ['consumer_id' => $this->consumer_id]),
                'rel' => 'consumption'
            ],
            [
                'href' => route('consumers.consumers.qtyallowedperdrink', ['consumer_id' => $this->consumer_id]),
                'rel' => 'qtyallowedperdrink'
            ],
            [
                'href' => route('consumers.consumers.consumptionperdrink', ['consumer_id' => $this->consumer_id]),
                'rel' => 'consumptionperdrink'
            ],
            [
                'href' => route('consumers.consumers.totalconsumption', ['consumer_id' => $this->consumer_id]),
                'rel' => 'totalconsumption'
            ]
        ];
    }
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

    public function totalConsumption($consumer_id)
    {
        return $this->getConnectionResolver()->connection()->select("SELECT
                SUM(total) total_consumed
            FROM
                (SELECT
                    COUNT(drink_id) * d.caffeine total
                FROM
                    consumption c
                JOIN drink d USING (drink_id)
                WHERE
                    c.created_at <= DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')
                        AND c.created_at >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
                        AND c.consumer_id = ?
                GROUP BY drink_id) total", [$consumer_id]);
    }
    public function consumptionPerDrink($consumer_id)
    {
        return $this->getConnectionResolver()->connection()->select("SELECT
                d.drink_id,
                d.name,
                d.caffeine,
                d.description,
                COUNT(c.consumption_id) qty_consumed,
                COUNT(c.consumption_id) * caffeine caffeine_consumed_total,
                IF(cdf.consumer_drink_favorite_id IS NOT NULL,
                    'Favorite',
                    'Not favorite') favorite
            FROM
                drink d
                    LEFT JOIN
                consumption c USING (drink_id)
                    LEFT JOIN
                consumer_drink_favorite cdf ON cdf.drink_id = c.drink_id
                    AND cdf.consumer_id = c.consumer_id
            WHERE
                c.created_at <= DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')
                    AND c.created_at >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
                    AND c.consumer_id = ?
                    OR c.drink_id IS NULL
            GROUP BY c.drink_id
            ORDER BY qty_consumed DESC", [$consumer_id]);
    }

    public function qtyAllowedPerDrink($consumer_id)
    {
        return $this->getConnectionResolver()->connection()->select("SELECT
                d.drink_id,
                d.name,
                FLOOR(((SELECT
                                consumption_limit
                            FROM
                                consumer c_in
                            WHERE
                                c_in.consumer_id = ?) - (SELECT
                                SUM(total) total_consumed
                            FROM
                                (SELECT
                                    COUNT(drink_id) * d.caffeine total
                                FROM
                                    consumption c
                                JOIN drink d USING (drink_id)
                                WHERE
                                    c.created_at <= DATE_FORMAT(NOW(), '%Y-%m-%d 23:59:59')
                                        AND c.created_at >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
                                        AND c.consumer_id = ?
                                GROUP BY drink_id) a)) / d.caffeine) qty_allowed,
                d.description,
                d.caffeine,
                IF(cdf.consumer_drink_favorite_id IS NOT NULL,
                    'Favorite',
                    'Not favorite') favorite
            FROM
                drink d
                    LEFT JOIN
                consumer_drink_favorite cdf ON cdf.drink_id = d.drink_id
                    LEFT JOIN
                consumer c ON cdf.consumer_id = c.consumer_id
            WHERE
                c.consumer_id = ?
                    OR c.consumer_id IS NULL", [$consumer_id, $consumer_id, $consumer_id]);
    }
}
