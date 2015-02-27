@extends('layout')

@section('content')
<div class='col-xs-9 maincolumn' style="background: none; width: 72%">
	<div class='row gameContent' style="width: 100%; height: 100%;">
		<section class="container" style="padding: 10px 10px; font-family: Verdana, Geneva, sans-serif; color: #333333; font-size: 0.9em;">
			<div class="row col-md-12" style="width: 76%;">
			<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
			<!-- Bootstrap v3.0.3 -->
			<!-- Instructions -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<strong>Instructions</strong>
					</div>

					<div class="panel-body">
						{{ $instructions }}
					</div>
				</div>
			<!-- End Instructions -->

    			@yield('gameForm')
    			



			<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
    		</div>
		</section>
	</div>
</div>

@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop