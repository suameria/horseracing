<?php

namespace App\Repositories\Calendar;

use App\Models\Calendar;

class CalendarRepository implements CalendarRepositoryInterface
{
    protected $calendar;

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    public function create(array $data)
    {
        return $this->calendar->create($data);
    }

    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->calendar->updateOrCreate($attributes, $data);
    }
    
    public function getCalendarByListKey($listKey)
    {
        return $this->calendar->where('list_key', $listKey)->first();
    }

}
