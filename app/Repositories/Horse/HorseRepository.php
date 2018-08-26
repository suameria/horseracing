<?php

namespace App\Repositories\Horse;

use App\Models\Horse;

class HorseRepository implements HorseRepositoryInterface
{
    protected $horse;

    public function __construct(Horse $horse)
    {
        $this->horse = $horse;
    }

    public function create(array $data)
    {
        return $this->horse->create($data);
    }

    public function updateOrCreate($horseKey, array $save)
    {
        return $this->horse->updateOrCreate(['horse_key' => $horseKey], $save);
    }

    public function getHorseByHorseKey($horseKey)
    {
        return $this->horse->where('horse_key', $horseKey)->first();
    }

    public function getNameHorseKeys()
    {
        $select = config('column.horses.name_horse_key');
        return $this->horse->select($select)
                    ->whereNull('name')
                    ->groupBy($select)
                    ->orderBy('horse_key', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

}
