<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = 'trainers';

    protected $fillable = [
        'id',
        'name',
        'name_kana',
        'training_center',
        'hometown',
        'birthday',
        'trainer_key',
        'created_at',
        'updated_at',
    ];
}
