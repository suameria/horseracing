@extends('admin.layouts.login')

@section('content')
{{ Form::open(['route' => 'admin.login.authenticate']) }}
  <div class="form-group has-feedback">
    {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Email']) }}
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
  </div>
  <div class="form-group has-feedback">
    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
  </div>
  <div class="row">
    <div class="col-4">
    </div>
    <!-- /.col -->
    <div class="col-4">
      <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div>
    <!-- /.col -->
    <div class="col-4">
    </div>
    <!-- /.col -->
  </div>
{{ Form::close() }}
@endsection