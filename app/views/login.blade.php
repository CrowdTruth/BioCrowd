@extends('layout')

@section('extraheaders')
	<script>
		//On window size < 480, set the ribbon logo div behind the ribbon button div, so it borders on the lower section properly.
		$(window).resize(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribimg").detach();
					$(detach).insertAfter($(this).find("#ribbutton"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribbutton").detach();
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
					$(detach).insertAfter($(this).find("#ribbutton"));
				})
			} else if (width > 480) {
				$("#ribbon").each(function() {
					var detach = $(this).find("#ribbutton").detach();
					$(detach).insertAfter($(this).find("#ribimg"));
				})
			}
		});
	</script>
@stop

@section('content')
	<div id="main">
			<div class="section group scrollto" id="ribbon">
				<div class="col span_4_of_8" id="ribimg">
					<img src="img/logo/logo_drexplorer.png" height="300px" id="logo"></img>
				</div>
				<div class="col span_4_of_8" id="ribbutton">
					 	<button onclick="location.href='#'">Start to help advance science</button>
				</div>	

			</div>
			
			<div class="section group scrollto"  id="info">
				<div class="col span_4_of_8">
					<div class="textblock">
					<H1>About {{ Lang::get('gamelabels.gamename') }}</H1>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
						"At vero eos et accusamus et iusto odio dignissimos ducimus
						qui blanditiis praesentium voluptatum deleniti atque corrupti
						quos dolores et quas molestias excepturi sint occaecatie."
						voluptatum deleniti atque corrupti quos dolores et quas.
						<bold>See how you can collect points</bold></p>
					</div>	
				</div>
				<div class="col span_4_of_8">
					<img src="img/glyphs/image_video.png" width="75%" style="padding-left: 15px;"></img>	
				</div>	
			</div>
		
			<div class="section group scrollto" id="campaigns">
				<div class="col span_4_of_8">
					<H1 class="sectiontitle"><strong>Active Now</strong></H1>
					<div id="campaignProgress">
						<div id="progress"><div class="bar" style="width: 50%;">50%</div></div>
						<div id="progress"><div class="bar" style="width: 30%;">30%</div></div>
						<div id="progress"><div class="bar" style="width: 20%;">20%</div></div>
					</div>
				</div>
				<div class="col span_4_of_8">
				<H1 class="sectiontitle"><strong>Campaigns</strong></H1>
				<div class="textblock">
					<p>"At vero eos et accusamus et iusto odio dignissimos ducimus
					qui blanditiis praesentium voluptatum deleniti atque corrupti
					quos dolores et quas molestias excepturi sint occaecatie."</p>
					</div>
				</div>	
			</div>
				<div class="section group scrollto" id="games">
					<div class="col span_8_of_8">
						<h1 class="sectiontitle">
							<strong>Level 1</strong>
						</h1>
						<div align="center">
						<ul id="gameslist ">
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/image_games-02.png"
									width="120px" />Cell tagging</a></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/image_games-03.png"
									width="120px" />Lorem Ipsum</a></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/image_games-03.png"
									width="120px" />Lorem Ipsum</a><img src=""
								alt=""></img></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/image_games-03.png"
									width="120px" />Lorem Ipsum</a><img src=""
								alt=""></img></li>
							<li><a data-ftrans="slide" href="#"><img
									alt="Cell tagging" src="img/icons/image_games-03.png"
									width="120px" />Lorem Ipsum</a><img src=""
								alt=""></img></li>
						</ul>
						</div>
					</div>

				</div>


	</div>
@stop
