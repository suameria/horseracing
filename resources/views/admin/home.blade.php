@extends('admin.layouts.app')

@section('title', '管理画面トップ')
@section('big_title', 'ダッシュボード')
@section('small_title', 'ホーム')

@section('content')
<div class="content-header">
    <h1 class="text-secondary">&nbsp;&nbsp;Dash Board</h1>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
    <a href="{{ route('admin.calendars.index') }}">
      <button type="button" class="btn btn-block btn-outline-primary btn-lg">
        <i class="fa fa-calendar-check-o"></i>&nbsp;
        Racing Calendar
      </button>
    </a>
    </div>
    </div>
  </div>
</section>
@endsection