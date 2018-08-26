<?php

namespace App\Repositories\Calendar;

interface CalendarRepositoryInterface
{
    public function create(array $data);

    public function updateOrCreate(array $attributes, array $data);

    public function getCalendarByListKey($listKey);
}