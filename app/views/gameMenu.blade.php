@extends('layout') 

@section('content')
	<div id="popUpDiv" style="display:none; padding:13px">
		<b style="font-size:13px; color:black;">How would you like to unlock this article and add it to your collection?</b><br />
		<a href="article.html"><button class='popupButton' style="width:100%; font-size:13px;" onclick="addedToCollection()">Read the article <br />cost: 10 <img class='coins' src='img/coins.png'></img></button></a>
		<a href="game_Interview_mockup_contributing_home.html" style="color:black;"><button class='popupButton' style="width:100%; font-size:13px;">Play games<br />earn more <img class='coins' src='img/coins.png'></img> </button></a>
		<a href="question_answer.html"><button class='popupButton' style="width:100%; font-size:13px;">Annotate this article<br /> earn: 10 <img class='coins' src='img/coins.png'></img></button></a>
		<a href="#" id="closeLink" ><b style="font-size:14px; color:black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close</b></a>
	</div>	

	<div class='col-xs-9 maincolumn' style="background:none; width:72%">
		<div class='row contentbox' style=" width:100%; height:100%; margin-top:17px;">
			<div class='col-xs-3' style='background:none;'></div>
	
			<div class='col-xs-6 gameModes' style='background:none;'>
				<center><h1 style="font-family:arial; font-size:28px; color:black;"><b>Welcome {{ Auth::user()->get()->name }}!</b></h1></center>
				<center><h1 style="font-family:arial; font-size:18px; color:#5E5C5C;"><b>Please select the type of game you would like to play</b></h1></center>

				@foreach($levels as $number => $items)
					@if ( $number > 0 )
						<div class='col-xs-12' style='background:none;'>
							<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level {{{ ($number+1) }}}</button>
						</div>
					@else
						<div class='col-xs-12' style='background:none;'>
							<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level {{{ $number+1 }}}</button>
						</div>
					@endif

					@foreach($items as $item)
						@if ( $item['enabled'] )
							<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
								<a href="{{{ $item['link'] }}}" style="color:#333"><center><img class='gameIcon' src='{{{ $item['image'] }}}'><br/>{{{ $item['text'] }}}</img></center></a>
							</div>
						@else
							<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
								<a href="" style="color:#333"><center><img class='gameIconDis' src='{{{ $item['image'] }}}'><br />{{{ $item['text'] }}}</img></center></a>
							</div>
						@endif
					@endforeach
				@endforeach
			</div>
			<div class='col-xs-3' style='background:white;'></div>
		</div>
	</div>	
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
