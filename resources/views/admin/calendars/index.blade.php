@extends('admin.layouts.app')

@section('title', 'Racing Calendar')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="m-0 text-dark">Racing Calendar {{ $year }}</h3>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">&nbsp;&nbsp;&nbsp;&nbsp;
              @foreach(config('constants.grade_param') as $get => $label)
              <li class="nav-item"><a class="nav-link {{ (request('grade') == $get) ? 'active' : '' }}" href="{{ route('admin.calendars.index', ['grade' => $get, 'year' => request('year'), 'month' => request('month')]) }}">{{ $label }}</a></li>
              @endforeach

              <select class="form-control select-year"  name="calendar-year">
                @for($i = $currentYear; $i >= 1986; $i-- )
                <option {{ (request('year') == $i) ? 'selected' : '' }} >{{ $i }}</option>
                @endfor
              </select>
            </ul>
          </div>
          <div class="card-header p-2">
            <ul class="nav nav-pills">&nbsp;&nbsp;&nbsp;&nbsp;
              @for($i = 1; $i <= 12; $i++)
              <li class="nav-item">
                <a class="nav-link {{ ($month == $i) ? 'active' : '' }}" href="{{ route('admin.calendars.index', ['month' => $i,'grade' => request('grade'), 'year' => request('year')]) }}">
                  {{ $i }}月
                </a>
              </li>
              @endfor
            </ul>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered" style="font-size: 13px; table-layout: fixed">
              <tr class="bg-gray-light">
                <th class="text-center width180">開催日</th>
                <th class="text-center width430">メインレース</th>
                <th class="text-center" colspan="3">着順</th>
              </tr>
              @foreach($calendars as $calendar)
              <tr>
                <td class="text-center">
                  <a href="{{ route('admin.schedules.index', $calendar->list_key) }}">
                    {{ $carbon->parse($calendar->date)->format('n月j日') }}
                    ({{ config('constants.week')[$carbon->parse($calendar->date)->format('w')] }})
                    {{ (int)substr($calendar->list_key, 4, 2) }}回
                    {{ config('constants.places')[substr($calendar->list_key, 2, 2)] }}
                    {{ (int)substr($calendar->list_key, 6, 2) }}日
                  </a>
                </td>
                <td class="text-left">
                  @if($calendar->race_key)
                  <a href="{{ route('admin.races.result', $calendar->race_key) }}">
                    {{ $calendar->title }}
                  </a>
                  @else
                  {{ $calendar->title }}
                  @endif

                  @if($calendar->grade)

                  @php
                  $gradeColor = '';
                  if ($calendar->grade == 1 || $calendar->grade == 4) {
                  $gradeColor = 'bg-danger';
                  } elseif ($calendar->grade == 2 || $calendar->grade == 5) {
                  $gradeColor = 'bg-warning';
                  } elseif ($calendar->grade == 3 || $calendar->grade == 6) {
                  $gradeColor = 'bg-success';
                  }
                  @endphp

                  <span class="float-right badge {{ $gradeColor }}">
                    {{ config('constants.grade')[$calendar->grade] }}
                  </span>

                  @endif
                </td>
                @php
                $races = $calendar->raceOrderOfFinishThree();
                @endphp
                @if($races->count())
                @foreach($races as $race)
                <td class="text-left">{{ $race->horse_name }}</td>
                @endforeach
                @else
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
                @endif
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@section('endbody')

@php
$mg = [
'month' => request('month') ?: $month,
'grade' => request('grade') ?: $grade ?: null,
'url'   => request()->url(),
];
$mg = json_encode($mg);
@endphp

@parent
<script>
    $(function () {
        $('select[name=calendar-year]').change(function () {
            if ($(this).val() != '') {
                mg = JSON.parse('@php echo $mg; @endphp');
                window.location.href = mg['url'] + '?year=' + $(this).val() + '&month=' + mg['month'] + '&grade=' + mg['grade'];
            }
        });
    });
</script>
@stop
@endsection