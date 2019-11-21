<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Module Solution</title>
    {!! Html::style('assets/bootstrap/css/bootstrap.css') !!}
    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/adminlte/dist/css/AdminLTE.css') !!}
    {!! Html::style('assets/adminlte/dist/css/skins/skin-green-light.css') !!}
    {!! Html::style('assets/lib/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::script('assets/js/jquery.min.js') !!}
    {!! Html::script('assets/bootstrap/js/bootstrap.min.js') !!}
    {!! Html::script('assets/adminlte/dist/js/app.js') !!}

    <style type="text/css">
      table { font-size: 14px; }
    </style>
  </head>
  <body class="hold-transition skin-green-light layout-top-nav">
    <div class="wrapper">  
    @yield('content')
    </div>
  </body>
  <script type="text/javascript">
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
    ]) !!};
  </script>
</html>
