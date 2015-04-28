@extends('layout') 

@section('extraheaders')
<link href="/css/campaignProgressBar.css" rel="stylesheet">
@stop

@section('content')
	<div class='col-xs-9 maincolumn' style="background:none; width:72%">
		<div class='row contentbox' style=" width:100%; height:100%; margin-top:17px;">
			<div class='col-xs-3' style='background:none;'></div>
	
			<div class='col-xs-12 gameModes' style='background:none;'>
				<center><h1 style="font-family:arial; font-size:28px; color:black;"><b>Welcome {{ Auth::user()->get()->name }}!</b></h1></center>
				<center><h1 style="font-family:arial; font-size:18px; color:#5E5C5C;"><b>Please select the campaign you would like to play</b></h1></center>
				<div class="table";>
				@foreach($campaignsByEndDate as $item)
						@if ( $item['enabled'] )
							<div class="Row">
								<div class="Cell col-xs-3">
									<div style='background:none; margin-bottom:10px; margin-top:10px;';>
										<a href="{{{ $item['link'] }}}" style="color:#333"><center align="center"><img class='gameIcon' src='{{{ $item['image'] }}}'><br/>{{{ $item['text'] }}}</img></center></a>
									</div>
								</div>
								<div class="Cell col-xs-9 campaignProgress">
									<div>
										Complete campaign "{{{ $item['text'] }}}" and earn the badge "{{{ $item['badgeName'] }}}"
									</div>
									<div class="progress">
										@if($item['numberPerformed']>$item['numberOfGamesInThisCampaign'])
											<div class="progress-bar" role="progressbar" aria-valuenow="100" area-valuemin="{{{$item['numberOfGamesInThisCampaign']}}}" aria-valuemax="{{{$item['numberOfGamesInThisCampaign']}}}" style="width:100%">
													100% Complete
											</div>
										@else
											<div class="progress-bar" role="progressbar" aria-valuenow="{{{$item['numberPerformed']}}}" area-valuemin="0" aria-valuemax="{{{$item['numberOfGamesInThisCampaign']}}}" style="width:{{($item['numberPerformed']/$item['numberOfGamesInThisCampaign'])*100}}%">
													{{($item['numberPerformed']/$item['numberOfGamesInThisCampaign'])*100}}% Complete
											</div>
										@endif
									</div>
								</div>
							</div>
						@else
							<div class="Row">
								<div class="Cell col-xs-3">
									<div style='background:none; margin-bottom:10px; margin-top:10px;'>
										<a href="" style="color:#333"><center><img class='gameIconDis' src='{{{ $item['image'] }}}'><br />{{{ $item['text'] }}}</img></center></a>
									</div>
								</div>
								<div class="Cell col-xs-9 campaignProgress">
									<div>
										You have already completed campaign "{{{ $item['text'] }}}" or the campaign period is over
									</div>
									<div class="progress">
										@if($item['numberPerformed']>$item['numberOfGamesInThisCampaign'])
											<div class="progress-bar" role="progressbar" aria-valuenow="100" area-valuemin="{{{$item['numberOfGamesInThisCampaign']}}}" aria-valuemax="{{{$item['numberOfGamesInThisCampaign']}}}" style="width:100%">
													100% Complete
											</div>
										@else
											<div class="progress-bar" role="progressbar" aria-valuenow="{{{$item['numberPerformed']}}}" area-valuemin="0" aria-valuemax="{{{$item['numberOfGamesInThisCampaign']}}}" style="width:{{($item['numberPerformed']/$item['numberOfGamesInThisCampaign'])*100}}%">
													{{($item['numberPerformed']/$item['numberOfGamesInThisCampaign'])*100}}% Complete
											</div>
										@endif
									</div>
								</div>
							</div>
						@endif
				@endforeach
				</div>
			</div>
			<div class='col-xs-3' style='background:white;'></div>
		</div>
	</div>	
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
