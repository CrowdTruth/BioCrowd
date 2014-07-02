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
				<center><h1 style="font-family:arial; font-size:28px; color:black;"><b>Welcome Dr. Wilson!</b></h1></center>
				<center><h1 style="font-family:arial; font-size:18px; color:#5E5C5C;"><b>Please select the type of game you would like to play</b></h1></center>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="factspan_contraction.html" style="color:#333"><center><img class='gameIcon' src='img/factorspan.png'><br />Factorspan</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="factspan_contraction.html" style="color:#333"><center><img class='gameIcon' src='img/factor_validation1.png'><br />Factor validation</a>
				</div>
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 1</button>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factorspan.png'><br />Factorspan II</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factor_validation1.png'><br />Factor validation II</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="reldir.html" style="color:#333"><center><img class='gameIcon' src='img/relation_direction.png'><br />Relation direction</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="relex.html" style="color:#333"><center><img class='gameIcon' src='img/relation_extraction.png'><br />Relation extraction</img></center></a>
				</div>
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 2</button>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factorspan.png'><br />Factorspan III</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factor_validation1.png'><br />Factor validation III</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_direction.png'><br />Relation direction II</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_extraction.png'><br />Relation extraction II</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="passage_alignment.html" style="color:#333"><center><img class='gameIcon' src='img/passage_alignment.png'><br />Passage alignment</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="question_answer.html" style="color:#333"><center><img class='gameIcon' src='img/question_answer.png'><br />Question answer</img></center></a>
				</div>
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 3</button>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factorspan.png'><br />Factorspan IV</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/factor_validation1.png'><br />Factor validation IV</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_direction.png'><br />Relation direction III</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_extraction.png'><br />Relation extraction III</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/passage_alignment.png'><br />Passage alignment II</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/question_answer.png'><br />Question answer II</img></center></a>
				</div>
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 4</button>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_direction.png'><br />Relation direction IV</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/relation_extraction.png'><br />Relation extraction IV</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/passage_alignment.png'><br />Passage alignment III</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/question_answer.png'><br />Question answer III</img></center></a>
				</div>
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 5</button>
				</div>	
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/passage_alignment.png'><br />Passage alignment IV</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/question_answer.png'><br />Question answer IV</img></center></a>
				</div>	
				<div class='col-xs-12' style='background:none;'>
					<button class='levelButton' style="width:100%; height: 35px; font-size:12px;" disabled><img src='img/lock.png' style="width:26px;height:26px;position:relative;left:-10px;top:0px"></img>Level 6</button>
				</div>	
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/challenging_mode.png'><br />Challenging mode</img></center></a>
				</div>
				<div class='col-xs-6' style='background:none; margin-bottom:10px; margin-top:10px;'>
					<a href="" style="color:#333"><center><img class='gameIconDis' src='img/survival_mode.png'><br />Survival mode</img></center></a>
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
