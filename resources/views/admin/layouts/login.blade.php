<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>KEIBA | Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset("adminltev3/dist/css/adminlte.min.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/iCheck/square/blue.css") }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>KEIBA</b>
  </div>
  <!-- /.login-logo -->

  <div class="login-box-body">
    @yield('content')
  </div>
  <!-- /.login-box-body -->

</div>
<!-- /.login-box -->

<script src="{{ asset("adminltev3/plugins/jquery/jquery.min.js") }}"></script>
<script src="{{ asset("adminltev3/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/iCheck/icheck.min.js") }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
  })
</script>
</body>
</html>
