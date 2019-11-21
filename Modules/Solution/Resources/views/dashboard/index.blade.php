@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
@section('content')

  <div class="content-wrapper">
    <iframe src="{{URL::to('/dashboard')}}" frameborder="0" style="width:100%; height:100%"></iframe>
  </div>

<script type="text/javascript">
  $(document).ready(function(){

    $('body').addClass('sidebar-collapse');

  })
</script>
@stop
