@extends('admin.layouts.app')

@section('title', 'レース詳細')

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
                <a class="nav-link {{ ($calendar->list_key == getRaceKeyWithoutRaceNo($raceKey)) ? 'active' : '' }}" href="{{ route('admin.races.result', $calendar->list_key . getRaceNumber($raceKey)) }}">{{ config('constants.places')[getPlaceNumber($calendar->race_key)] }}</a>
              </li>
              @endforeach
              &nbsp;&nbsp;&nbsp;&nbsp;
              @for($i = 1; $i <= 12; $i++)
              <li class="nav-item">
                <a class="nav-link {{ (getRaceNumber($schedule->race_key) == str_pad($i, 2, 0, STR_PAD_LEFT)) ? 'active' : '' }}" href="{{ route('admin.races.result', getRaceKeyWithoutRaceNo($schedule->race_key) . str_pad($i, 2, 0, STR_PAD_LEFT)) }}">
                  {{ $i }}R
                </a>
              </li>
              @endfor
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="info-box">
          <span class="info-box-icon bg-primary">{{ $schedule->race }}R</span>

          <div class="info-box-content">
            <h4>
              {{ $schedule->title }}
              <small>
                &nbsp;{{ $schedule->date->format('Y年n月d日 H時i分発走') }}
              </small>
            </h4>
            <span class="text-secondary">
              {{ $schedule->detail_1 }}&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ $schedule->detail_2 }}&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ $schedule->detail_3 }}&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ $schedule->detail_4 }}&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ $schedule->detail_5 }}&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ $schedule->detail_6 }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.races.denma', $schedule->race_key) }}">出馬表</a>
              </li>
              @if($schedule->status === 1)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.races.result', $schedule->race_key) }}">レース結果</a>
              </li>
              @endif
              <li class="nav-item">
                <a class="nav-link active show" href="{{ route('admin.races.pillar5', $schedule->race_key) }}">馬柱5</a>
              </li>
            </ul>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered" style="font-size: 14px; table-layout: fixed">
              <tr class="bg-success">
                <th class="text-center width55">印</th>
                <th class="text-center width35">枠</th>
                <th class="text-center width35">馬</th>
                <th class="text-center width200"></th>
                <th class="text-center">前走</th>
                <th class="text-center">2走前</th>
                <th class="text-center">3走前</th>
                <th class="text-center">4走前</th>
                <th class="text-center">5走前</th>
              </tr>
              @foreach ($schedule->races as $race)
              <tr class="{{ (isset($race->horse_number) && $race->horse_weight == 0) ? 'bg-gray-light' : null}}">
                <td class="text-center">
                  @if(isset($race->horse_number) && $race->horse_weight == 0)
                  <b><span class="text-danger">取消</span></b>
                  @else
                  <select class="form-control">
                    <option>---</option>
                    <option>◎</option>
                    <option>◯</option>
                    <option>▲</option>
                    <option>△</option>
                    <option>☆</option>
                    <option>消</option>
                  </select>
                  @endif
                </td>
                <td class="text-center">{{ $race->post_position }}</td>
                <td class="text-center">{{ $race->horse_number }}</td>
                <td>
              <big>
                <b><a href="#">{{ $race->horse_name }}</a></b>&nbsp;
              </big><br>
              [ {{ $race->horse_sex }}{{ $race->horse_age }} ]&nbsp;

              @if(isset($race->horse_number) && $race->horse_weight == 0)
              <b><span class="text-secondary">*</span></b> 倍&nbsp;
              ( <b><span class="text-secondary">*</span></b> 人気 )
              @else
              <b><span class="text-danger">{{ $race->odds }}</span></b> 倍&nbsp;
              ( <b><span class="text-success">{{ $race->favorite }}</span></b> 人気 )
              @endif
              <br>
              <small>
                父　　 : {{ $race->horse->father->name or null }}<br>
                母　　 : {{ $race->horse->mother->name or null }}<br>
                母父　 : {{ $race->horse->mother->father->name or null }}<br>
                騎手　 : <a href="#" class="text-info">{{ $race->jockey_name or null }}</a>&nbsp;[ {{ (int)$race->weight }} ]<br>
                調教師 : <a href="#" class="text-info">{{ $race->trainer_name or null }}</a>&nbsp;[ {{ $race->trainer->training_center or null }} ]
              </small>
              </td>

              @php
              $runCount = 0;
              @endphp

              @foreach($race->pillar5 as $pillar)

              @php
              if ($runCount >= 5) break;
              @endphp

              @if($pillar)

              @php
              if (isset($race->horse_number) && $race->horse_weight == 0) {
              $badgeColor = 'bg-gray-light';
              $backColor  = 'bg-gray-light';
              } elseif ($pillar->order_of_finish == 1) {
              $badgeColor = 'bg-warning';
              $backColor  = 'first-bg-color';
              } elseif ($pillar->order_of_finish == 2) {
              $badgeColor = 'bg-primary';
              $backColor  = 'second-bg-color';
              } elseif ($pillar->order_of_finish == 3) {
              $badgeColor = 'bg-danger';
              $backColor  = 'third-bg-color';
              } else {
              $badgeColor = 'bg-gray-light';
              $backColor  = '';
              }
              @endphp

              <td class="{{ $backColor }}">

                {{-- ex. 3 18.04.08 阪神 --}}
                <span class="pillar-badge {{ $badgeColor }}">
                  {{ $pillar->order_of_finish }}
                </span>&nbsp;
                <small>
                  {{ $pillar->date->format('Y.m.d') }} {{ config('constants.places')[getPlaceNumber($pillar->race_key)] }}<br>

                  {{-- ex. 桜花賞G1 --}}
                  <b>{{ $pillar->title }}</b><br>

                  @if ($pillar->order_of_finish != 0)

                  {{-- ex. 芝1600 1:33.5 良 --}}
                  {{ $pillar->detail_1 }} {{ $pillar->preRace[$pillar->order_of_finish-1]->time }} {{ $pillar->detail_3 }}<br>

                  {{-- ex. 17頭1番1人 石橋脩(55) --}}
                  {{ $pillar->preRace->count() }}頭{{ $pillar->horse_number }}番{{ $pillar->favorite }}人&nbsp;
                  {{ $pillar->jockey_name }}({{ (int)$pillar->weight }})<br>



                  {{-- ex. 6-6 (34.3)  498(0) --}}
                  {{ $pillar->preRace[$pillar->order_of_finish-1]->passing }}&nbsp;
                  ({{ $pillar->preRace[$pillar->order_of_finish-1]->three_furlong }})&nbsp;&nbsp;
                  {{ $pillar->preRace[$pillar->order_of_finish-1]->horse_weight }}&nbsp;
                  @if ($pillar->preRace[$pillar->order_of_finish-1]->change_weight === 0)
                  ({{ $pillar->preRace[$pillar->order_of_finish-1]->change_weight }})
                  @else
                  ({{ ($pillar->preRace[$pillar->order_of_finish-1]->sign) ? '+' : '-' }}{{ $pillar->preRace[$pillar->order_of_finish-1]->change_weight }})
                  @endif
                  <br>

                  {{-- ex. アーモンドアイ(0.4) --}}
                  {{-- 自分自身が1着だった場合 2着の馬名と2着との差 --}}
                  @if($pillar->order_of_finish-1 == 0)
                    {{ $pillar->preRace[1]->horse_name }}&nbsp;
                    ({{ round(convertStrTimeToSecond($pillar->preRace[0]->time) - convertStrTimeToSecond($pillar->preRace[1]->time), 2) }})
                  @else
                    {{ $pillar->preRace[0]->horse_name }}&nbsp;
                    ({{ round(convertStrTimeToSecond($pillar->preRace[$pillar->order_of_finish-1]->time) - convertStrTimeToSecond($pillar->preRace[0]->time), 2) }})
                  @endif

                  @endif

                </small>
              </td>
              @endif

              @php
              $runCount++;
              @endphp

              @endforeach

              @if($runCount < 5)
              @for($i = $runCount; $i < 5; $i++)
              <td></td>
              @endfor
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
@endsection