<?php

namespace App\Repositories\Jockey;

use App\Models\Jockey;

class JockeyRepository implements JockeyRepositoryInterface
{
    protected $jockey;

    public function __construct(Jockey $jockey)
    {
        $this->jockey = $jockey;
    }

    public function create(array $data)
    {
        return $this->jockey->create($data);
    }

    public function updateOrCreate($jockeyKey, array $save)
    {
        return $this->jockey->updateOrCreate(['jockey_key' => $jockeyKey], $save);
    }

    public function getJockeyByJockeyKey($jockeyKey)
    {
        return $this->jockey->where('jockey_key', $jockeyKey)->first();
    }

    public function getNameJockeyKeys()
    {
        $select = config('column.jockeys.name_jockey_key');
        return $this->jockey->select($select)
                    ->whereNull('name')
                    ->groupBy($select)
                    ->orderBy('jockey_key', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}
