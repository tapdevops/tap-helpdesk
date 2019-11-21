<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse slimscrollsidebar">
		<div class="user-profile">
			<div class="dropdown user-pro-body">
				<div>{!! Html::image('plugins/images/logo.png', 'user-img') !!}</div>
			</div>
		</div>
		<ul class="nav" id="side-menu" style="background-color:#f8f8f8;">
			<li> 
				<a class="waves-effect">
					<i data-icon=")" class="linea-icon linea-basic fa-fw"></i> 
					<span class="hide-menu">Ticket Information</span>
				</a>
			</li>
		</ul>

		@foreach ($info as $row)
		<div class="col-lg-12 col-sm-12">
			<div class="col-in row">
				<div class="col-md-6 col-sm-6 col-xs-6"> 
					<i data-icon="{{$row['icon']}}" class="linea-icon linea-basic"></i>
					<h5 class="text-muted vb">{{$row['name']}}</h5>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<h3 class="counter text-right m-t-15 text-{{$row['color']}}">{{$row['value']}}</h3>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="progress">
						<div class="progress-bar progress-bar-{{$row['color']}}" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
					</div>
				</div>
			</div>
		</div>
		@endforeach

	</div>
</div>