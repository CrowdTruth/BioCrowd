@extends('layout')

@section('content')
	<div class="main">
		<!--/////////////////////////////////////////GAME CONTENT/////////////////////////////////////////////////////////////////////-->
		<!-- Bootstrap v3.0.3 -->
		<!-- Instructions -->
		<div id="sidebar" style="display: none;"></div>
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

		<div class="examplePopup" align="center">
			<div class="examples">{{ $examples }}</div>
		</div>
		
		<div class="section group" id="info_container">
			<table style="height:300px">
				<tr style="width: 100%; height: 50%;">
					<td>
						{{ $steps }}
					</td>
				</tr>
				<tr style="width: 100%; height: 50%;">
					<td>
						<div class="col span_4_of_8" id="startbutton" style="width: 90%">
							<button onclick="location.href='#'" class="startgame"  style="float: right">Start game</button>
							<button class="closeTutorial">close tutorial</button>
						</div>
					</td>
				</tr>
			</table>
		</div>
		
		@if($campaignMode)
				{{ Form::open([ 'url' => 'submitCampaign', 'name' => 'annotationForm' ]) }}
				{{ Form::hidden('campaignIdArray', serialize($campaignIdArray)) }}
				@if(isset($gameOrigin) && $gameOrigin)
				{{ Form::hidden('gameOrigin', $gameOrigin) }}
				@endif
			@else
				{{ Form::open([ 'url' => 'submitGame', 'name' => 'annotationForm' ]) }}
			@endif
		
		@yield('gameForm')
		
		{{ Form::close() }}
		
		<div class="section group">
			<div class="col span_8_of_8">
				<table style="width:100%">
					<tr style="width:100%">
						<td style="width: 20%; text-align: left;"><button class="goHome" title="Back to Crowdtruth Games" onclick="location.href='http://game.crowdtruth.org'">Crowdtruth Games</button></td> <!-- TODO: make this url and the name of "Crowdtruth Gams" a parameter -->
						<td style="width: 20%; text-align: left;"><button class="goGameSelect" title="Back to game select" onclick="location.href='{{ Lang::get('gamelabels.gameUrl') }}'">Game Select</button></td>			
						<td style="width: 60%; text-align: right;">Want to skip this image?&nbsp;&nbsp;<button class="goNextImage" title="Want to skip this image? Click here for the next one"   onclick="location.href='Game1.html'">Next image</button></td>
					</tr>
				</table>

			</div>	
			
		</div>
		<!--/////////////////////////////////////////END GAME CONTENT/////////////////////////////////////////////////////////////////////-->
	</div>
@stop