<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';

    protected $fillable = [
        'id',
        'schedule_id',
        'order_of_finish',
        'price',
        'favorite',
        'type',
        'race_key',
        'created_at',
        'updated_at',
    ];
}
