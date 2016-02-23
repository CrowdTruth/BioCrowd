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
				{{ Form::open(array('url' => 'register', 'method' => 'POST', 'role' => 'form')) }}
					{{ Form::hidden('email', Session::getId().'@anonymous-user.com') }}
					{{ Form::hidden('name', 'guest_user') }}
					{{ Form::hidden('password','')}}
					{{ Form::hidden('password2','') }}
					{{ Form::hidden('code', 'm0ng0') }}
					{{ Form::submit('Start to help advance science', array('class' => 'anonymous-loginButton')) }}
				{{ Form::close() }}
			</div>	

		</div>
		
		<div class="section group scrollto"  id="info">
			<div class="col span_4_of_8">
				<div class="textblock">
				<H1>About {{ Lang::get('gamelabels.gamename') }}</H1>
				<p>BioCrowd is a crowdsourcing platform where biology experts annotate microscopic images. Different annotation tasks 
				are represented in various mini-games, such as counting of cells, localization of colonies and classification of vesicle locations. 
				These are domain specific tasks that require expertise beyond what the general crowd can provide. We employ a variety of gaming 
				elements such as badges, points and levels in order to motivate and optimize the experts effort. BioCrowd is part of our CrowdTruth 
				pipeline where machine processing, paid crowdsourcing and nichesourcing come together. BioCrowd aims to engage an active community 
				of experts, to advance biology and specifically cancer research.</bold></p>
				</div>	
			</div>
			<div class="col span_4_of_8">
				<img src="img/glyphs/image_video.png" width="75%" style="display:block; margin:auto;"></img>	
			</div>	
		</div>
		<?php 
		//grab the progress of the top 3 running campaigns that aren't done
		$campaigns = Campaign::where('targetNumberAnnotations','>','0')
		->join('judgements', 'campaigns.id', '=', 'judgements.campaign_id')
		//->where('flag','!=','\'skipped\'')
		//->where('flag','!=','\'incomplete\'')
		->join('campaign_has_game', 'campaigns.id', '=', 'campaign_has_game.campaign_id')
		->selectRaw('campaigns.id,tag,targetNumberAnnotations,
															(
																(
																	count(distinct user_id,task_id,judgements.campaign_id)
																)/targetNumberAnnotations
															)*100 as \'order\'')
		->groupBy('tag')
		->orderBy('orders')
		->take(3)
		->get();
		//dd($campaigns);
		//get all campaign id's from the campaigns that are in the $campaigns array
		$campaignIdsArray = [];
		foreach ($campaigns as $campaign){
			array_push($campaignIdsArray,$campaign->id);
		}
		
		$extracampaigns = [];
		if(count($campaigns) < 3){
			//if there are less then 3 campaigns shown this way, add some more if possible
			$extracampaigns = Campaign::where('targetNumberAnnotations','>','0')
			->whereNotIn('id',$campaignIdsArray)
			->take((3-count($campaigns)))->get();
		}
		?>
		<div class="section group scrollto" id="campaigns">
			<div class="col span_4_of_8">
				<H1 class="sectiontitle"><strong>Active Campaigns</strong></H1>
				<div id="campaignProgress">
					@foreach ($campaigns as $campaign)
						@if ($campaign->order < 100)
							<div>{{$campaign->tag}}</div><div id="progress"><div class="bar" style="width: {{$campaign->order}}%;">{{$campaign->order}}%</div></div>
						@else
							<div>{{$campaign->tag}}</div><div id="progress"><div class="bar" style="width: 100%;">100%</div></div>
						@endif
					@endforeach
					@foreach ($extracampaigns as $extracampaign)
						<div>{{$extracampaign->tag}}</div><div id="progress"><div class="bar" style="width: 0%;">0%</div></div>
					@endforeach
				</div>
			</div>
			<div class="col span_4_of_8">
			<H1 class="sectiontitle"><strong>Campaigns</strong></H1>
			<div class="textblock">
				<p>Some annotations have a more pressing need to be done. This is where campaigns come in; 
				If you do all the games of a campaign you will get a bonus score for finishing the campaign. 
				Try the tutorial campaign, it entails playing all minigames at least once. </p>
				</div>
			</div>	
		</div>
	</div>
@stop
