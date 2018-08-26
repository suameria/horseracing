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
              <li class="nav-item">
                <a class="nav-link active show" href="{{ route('admin.races.result', $schedule->race_key) }}">レース結果</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.races.pillar5', $schedule->race_key) }}">馬柱5</a>
              </li>
            </ul>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered" style="font-size: 13px; table-layout: fixed">
              <tr class="bg-success">
                <th class="text-center width55">印</th>
                <th class="text-center width35">着</th>
                <th class="text-center width35">枠</th>
                <th class="text-center width35">馬</th>
                <th class="text-center">馬名</th>
                <th class="text-center width55">性齢</th>
                <th class="text-center width55">斤量</th>
                <th class="text-center width100">騎手</th>
                <th class="text-center width55">タイム</th>
                <th class="text-center width70">着差</th>
                <th class="text-center width45">人気</th>
                <th class="text-center width70">オッズ</th>
                <th class="text-center width55">3F</th>
                <th class="text-center width85">コーナー</th>
                <th class="text-center width100">厩舎</th>
                <th class="text-center width65">馬体重</th>
              </tr>
              @foreach ($races as $race)
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
                <td class="text-center">{{ $race->order_of_finish }}</td>
                <td class="text-center">{{ $race->post_position }}</td>
                <td class="text-center">{{ $race->horse_number }}</td>
                <td><a href="#">{{ $race->horse_name }}</a></td>
                <td class="text-center">{{ $race->horse_sex }}{{ $race->horse_age }}</td>
                <td class="text-center">{{ substr($race->weight, 0, 4) }}</td>
                <td><a href="#">{{ $race->jockey_name }}</a></td>
                <td class="text-center">{{ $race->time }}</td>
                <td class="text-center">{{ $race->margin_disp }}</td>
                <td class="text-center">{{ $race->favorite }}</td>
                <td class="text-right">{{ $race->odds }}</td>
                <td class="text-center">{{ $race->three_furlong }}</td>
                <td class="text-right">{{ $race->passing }}</td>
                <td><a href="#">{{ $race->trainer_name }}</a></td>
                <td class="text-right">
                  @if ($race->horse_weight)
                  {{ $race->horse_weight }}
                  @if ($race->change_weight === 0)
                  ({{ $race->change_weight }})
                  @else
                  ({{ ($race->sign) ? '+' : '-' }}{{ $race->change_weight }})
                  @endif
                  @endif
                </td>
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