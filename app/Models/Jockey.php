<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jockey extends Model
{
    protected $table = 'jockeys';

    protected $fillable = [
        'id',
        'name',
        'name_kana',
        'blood',
        'height',
        'weight',
        'training_center',
        'belonging',
        'hometown',
        'birthday',
        'jockey_key',
        'trainer_key',
        'created_at',
        'updated_at',
    ];
}
