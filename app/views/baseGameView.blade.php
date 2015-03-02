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
					@if($campaignMode)
						{{ $story }}
					@else
						{{ $instructions }}
					@endif
					</div>
				</div>
			<!-- End Instructions -->
			@if($campaignMode)
				{{ Form::open([ 'url' => 'submitCampaign', 'name' => 'annotationForm' ]) }}
				{{ Form::hidden('campaignId', $campaignId) }}
				{{ Form::hidden('numberPerformed', $numberPerformed) }}
				{{ Form::hidden('amountOfGamesInThisCampaign', $amountOfGamesInThisCampaign) }}
			@else
				{{ Form::open([ 'url' => 'submitGame', 'name' => 'annotationForm' ]) }}
			@endif

    			@yield('gameForm')
			{{ Form::close() }}


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