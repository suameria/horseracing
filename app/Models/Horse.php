<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    protected $table = 'horses';

    protected $fillable = [
        'id',
        'name',
        'name_detail',
        'sex',
        'coat_color',
        'birthday',
        'owner',
        'breeder',
        'hometown',
        'status',
        'horse_key',
        'f_horse_key',
        'm_horse_key',
        'trainer_key',
        'created_at',
        'updated_at',
    ];

    public function father()
    {
        return $this->hasOne(Horse::class, 'horse_key', 'f_horse_key');
    }

    public function mother()
    {
        return $this->hasOne(Horse::class, 'horse_key', 'm_horse_key');
    }

}
