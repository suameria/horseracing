@extends('admin.layouts.app')

@section('title', 'レース一覧')

@section('content')
<div class="content-header">
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              @foreach($calendars as $calendar)
              <li class="nav-item">
                <a class="nav-link {{ ($calendar->date == $listKeyDate) ? 'active' : '' }}" href="{{ route('admin.schedules.index', $calendar->list_key) }}">{{ $calendar->date->format('n/j') }}({{ config('constants.day_of_week')[$calendar->date->format('w')] }})</a>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($schedules as $name => $schedule)
      @if($schedule->count())
      <div class="col-md-4">
        <div class="card card-widget widget-user-2" style="font-size: 14px;">
          <div class="widget-user-header bg-info-gradient">
            <h4>{{ $name }}</h4>
          </div>
          <div class="card-footer p-0">
            <ul class="nav flex-column">
              @foreach ($schedule as $race)
              <li class="nav-item">
                @if($race->status)
                <a href="{{ route('admin.races.result', $race->race_key) }}" class="nav-link">
                  {{ $race->title }}
                  <span class="float-right badge bg-danger">
                    {{ $race->race }}&nbsp;R&nbsp;&nbsp;&nbsp;{{ $race->date->format('H:i') }}
                  </span>
                </a>
                @else
                <a href="{{ route('admin.races.denma', $race->race_key) }}" class="nav-link">
                  {{ $race->title }}
                  <span class="float-right badge bg-success">
                    {{ $race->race }}&nbsp;R
                  </span>
                </a>
                @endif
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      @endif
      @endforeach
    </div>
  </div>
</section>
@endsection