<!DOCTYPE html>
<html>
<head>
  {{-- title --}}
  <title>@yield('title')</title>

  {{-- meta --}}
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/font-awesome/css/font-awesome.min.css") }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset("adminltev3/dist/css/adminlte.css") }}">
  <link rel="stylesheet" href="{{ asset("css/admin.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/iCheck/flat/blue.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/morris/morris.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/datepicker/datepicker3.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/daterangepicker/daterangepicker-bs3.css") }}">
  <link rel="stylesheet" href="{{ asset("adminltev3/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-collapse">
  <div class="wrapper">
    {{-- Header --}}
    @include('admin.layouts.header')

    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')

    <div class="content-wrapper">

      {{-- Content --}}
      @yield('content')

    </div>
  </div>

@section('endbody')
<script src="{{ asset("adminltev3/plugins/jquery/jquery.min.js") }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ asset("adminltev3/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset("adminltev3/plugins/morris/morris.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/sparkline/jquery.sparkline.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/knob/jquery.knob.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{ asset("adminltev3/plugins/daterangepicker/daterangepicker.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
<script src="{{ asset("adminltev3/plugins/fastclick/fastclick.js") }}"></script>
<script src="{{ asset("adminltev3/dist/js/adminlte.js") }}"></script>
<script src="{{ asset("adminltev3/dist/js/demo.js") }}"></script>
@show{{-- Javascript --}}
</body>
</html>
