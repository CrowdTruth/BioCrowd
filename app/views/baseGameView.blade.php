@extends('layout')

@section('content')
	<div class="section group" id="mainsection">
		<div class="col" id="main">
			<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
			<!-- Bootstrap v3.0.3 -->
			<!-- Instructions -->
			<div class="section group nobg" id="ribbon">
				<div class="col span_3_of_8" id="ribimg">
					<img src="img/logo/logo_drexplorer_game.png" height="300px" id="logo"
						alt=""></img>
				</div>
				<div class="col span_5_of_8" id="ribbutton">
					<div class="textblock">
						@if(isset($story) && $campaignMode)
							<h1>Story</h1>
							{{ $story }}
						@else
							<h1>Instructions for game {{$gameName}}</h1>
							{{ $instructions }}
						@endif
					</div>
				</div>
	
			</div>
	
			<div class="examplePopup section group" align="center">
				<div class="examples">{{ $examples }}</div>
				<div class="steps textblock" id="info_container">{{ $steps }}</div>
			</div>
			
			@if($campaignMode)
				{{ Form::open([ 'url' => 'submitCampaign', 'name' => 'annotationForm' ]) }}
				{{ Form::hidden('campaignIdArray', serialize($campaignIdArray)) }}
				{{ Form::hidden('currentlyPlayedCampaignId', Input::get('campaignId')) }}
				@if(isset($gameOrigin) && $gameOrigin)
				{{ Form::hidden('gameOrigin', $gameOrigin) }}
				@endif
			@else
				{{ Form::open([ 'url' => 'submitGame', 'name' => 'annotationForm' ]) }}
			@endif
			
			@yield('gameForm')
			
			{{ Form::close() }}
			<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
			<div class="section group">
				<div class="col span_8_of_8">
					<table style="width:100%">
						<tr style="width:100%">
							<td style="width: 20%; text-align: left;"><button type="button" id="crowdTruthGamesButton" class="goHome bioCrowdButton" title="Back to Crowdtruth Games" onclick="location.href='http://game.crowdtruth.org'">Crowdtruth Games</button></td> <!-- TODO: make this url and the name of "Crowdtruth Gams" a parameter -->
							<td style="width: 20%; text-align: left;"><button type="button" id="selectAnotherGameButton" class="goGameSelect bioCrowdButton" title="Back to game select" onclick="location.href='{{ Lang::get('gamelabels.gameUrl') }}'">Select Other Game</button></td>			
							<td style="width: 60%; text-align: right;"></td>
						</tr>
					</table>
				</div>	
			</div>
		</div>
		
		@if (Auth::user()->check())
		<!-- Begin sidebar -->
		@include('sidebar')
		<!-- End sidebar -->
		@endif
		
	</div>
@stop