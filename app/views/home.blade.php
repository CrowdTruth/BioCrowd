@extends('layout')

@section('extraheaders')
	<link href="css/gamemenu.css" rel="stylesheet">
	<script type="text/javascript">
		//On window size < 480, set the ribbon logo div behind the ribbon button div, so it borders on the lower section properly.
		$(window).resize(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribimg").detach();
					$(detach).insertAfter($(this).find("#ribinfo"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribinfo").detach();
					$(detach).insertAfter($(this).find("#ribimg"));
				})
			}
		});
	
		//Reset logo div in front of button on larger size.
		$(document).ready(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribimg").detach();
					$(detach).insertAfter($(this).find("#ribinfo"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribinfo").detach();
					$(detach).insertAfter($(this).find("#ribimg"));
				})
			}
		});
	</script>
@stop

@section('content')
	<div class="section group" id="mainsection">
			<div class="col span_6_of_8" id="main">

				<div class="section group scrollto home" id="ribbon">
					<div class="col span_4_of_8" id="ribimg">
						<img src="img/logo/logo_drexplorer_loggedin.png" height="300px"
							id="logo" alt=""></img>
					</div>
					<div class="col span_4_of_8" id="ribinfo">
						<div class="textblock"><p>
							{{ Lang::get('gamelabels.gameOverviewText') }}<br>
							<!-- strong>See how you can redeem your points
							and claim your award!</strong> <a href="#"><img src="img/glyphs/play-02.png" height=20px></img></a -->
						</p></div>
					</div>

				</div>

				<div class="section group scrollto" id="games" >
					<div class="col span_8_of_8">
					<div align="center">
						<a href="playCampaign?campaignId=3"><button class="bioCrowdButton" style="background-color:#FECD08; color: black; font-weight: bold;">Surprise me</button></a>
					</div>
					@foreach($levels as $number => $items)
						@if ( $number+1 > Auth::user()->get()->level )
							<h1 class="sectiontitle">
								<strong style="padding-right: 10px;">Level {{{ $number+1 }}}</strong><img src='img/lock.png' style="width:26px;height:26px;position:relative;"></img>
							</h1>
						@else
							<h1 class="sectiontitle">
							<strong>Level {{{ $number+1 }}}</strong>
							</h1>
						@endif
						<div align="center">
							<ul id="gameslist">
								@foreach($items as $item)
									@if ( $item['enabled'] )
										<li><a data-ftrans="slide" href="{{{ $item['link'] }}}"><img id="gameMenu {{ $item['text'] }}"
										alt="{{{ $item['text'] }}}" src="{{{ $item['image'] }}}"
										width="120px" /><div style="max-width:120px"><strong id="gameMenu {{ $item['text'] }} text">{{{ $item['text'] }}}</strong></div></a></li>
									@else
										<li><div style="color:white;"><img
										alt="Cell tagging" src="img/icons/image_games-03.png"
										width="120px" /><div style="max-width:120px"><strong>{{{ $item['text'] }}}</strong></div></div></li>
									@endif
								@endforeach
							</ul>
						</div>
					@endforeach
					</div>

				</div>

				<div class="section group scrollto" id="quickies">
					<div class="col span_8_of_8">
						<h1 class="sectiontitle">
							<strong>Quick Play</strong> play in less than 5 minutes
						</h1>
						<div align="center">
						<ul id="quickieslist">
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/quickies-02.png" width="120px" />4
									minutes</a></li>
							<li ><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/quickies-03.png" width="120px" />3
									minutes</a></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/quickies-04.png" width="120px" />3
									minutes</a><img src="" alt=""></img></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/quickies-05.png" width="120px" />2
									minutes</a><img src="" alt=""></img></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/quickies-06.png" width="120px" />3
									minutes</a><img src="" alt=""></img></li>
						</ul>
						</div>
					</div>

				</div>

				<div class="section group scrollto" id="instructions">
					<div class="section group">
						<div class="col span_8_of_8">
							<h1 class="sectiontitle">
								<strong>Instructions</strong> How to play
							</h1>
						</div>
					</div>
					<div class="section group">
						<div class="col span_4_of_8">
							<img src="img/glyphs/image_video.png" width="75%" alt=""></img>
						</div>
						<div class="col span_4_of_8">
							<div class="textblock">
							<span >At vero eos et accusamus et iusto odio dignissimos
								ducimus qui blanditiis. <strong>I want to learn more.</strong>
							</span>
							</div>
						</div>
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