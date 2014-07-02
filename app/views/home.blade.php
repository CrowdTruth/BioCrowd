@extends('layout') 

@section('content')
	<div id="popUpDiv" style="display:none; padding:13px; top:200px;">
		<b style="font-size:13px; color:black;">How would you like to unlock this paper and add it to your medical library?</b><br />
		<a href="article.html"><button class='popupButton' style="width:100%; font-size:13px;" onclick="addedToCollection()">Read the paper <br />cost: 10 <img class='coins' src='img/img/'medical_logo.png'></img></button></a>
		<a href="game_Interview_mockup_contributing_home.html" style="color:black;"><button class='popupButton' style="width:100%; font-size:13px;">Play games<br />earn more <img class='coins' src='img/coins.png'></img> </button></a>
		<a href="question_answer.html"><button class='popupButton' style="width:100%; font-size:13px;">Annotate this paper<br /> earn: 10 <img class='coins' src='img/coins.png'></img></button></a>
		<a href="#" id="closeLink" ><b style="font-size:14px; color:black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close</b></a>
	</div>	
	<div class='col-xs-9 maincolumn' style="background:none; width:72%">
		<div class='row '>
			<div class='col-xs-6 content_box' style='background:none' >
				<h1 style='background:#424242; color:#F8F8F8;  border-radius:10px; border: 1px solid grey; padding:5px;  font-size:22px;'>&nbsp;&nbsp;New literature <b style="font-size:12px; position:relative; top:0px; left:158px;">Costs: 15</b> <img class='coins' style="position:relative; left:158px;" src='img/coins.png'></img></h1>
				<h5 style='background:white; position:relative; border-radius:10px; border:solid 1px; border-top-left-radius:0px; border-top-right-radius:0px;  border-color:#919090; top:-17px; height:70%; padding:5px; solid rgb(200, 200, 200); '>
					<span style=" position:relative;left:10px;"><br /><b><a href="#" style="color:#333;" id="link">Body-Mass Index and mortality among Adults with Incident Type 2 Diabetes</a></b><br />The relation between body weight and mortality among persons with type 2 diabetes remains unsolved..</span><br />
					<span style=" position:relative;left:10px;"><br /><b><a href="#" style="color:#333;" id="link">Young blood reverses age-related impairments in cognitive function and synaptic plasticity in mice </a></b><br />As human lifespan increases, a greater fraction of the population is suffering from age-related cognitive impairments, making it important to elucidate a means to combat the effects of aging. </span>
					<span style=" position:relative;left:90%;"><br /><b><a href="#" style="color:#464945;" onclick="fullVersion()">More</a></b></span>
				</h5>
			</div>
			<div class='col-xs-6 content_box'>
				<h1 style='background:#424242; color:#F8F8F8;  border-radius:10px;  border: 1px solid grey; padding:5px;  font-size:22px;'>&nbsp;&nbsp;High urgency literature <b style="font-size:12px; position:relative; top:0px; left:74px;">Costs: 10</b> <img class='coins' style="position:relative; left:74px;" src='img/coins.png'></img></h1>
				<h5 style='background:white; position:relative;  border-radius:10px; border:solid 1px; border-top-left-radius:0px; border-top-right-radius:0px;  border-color:#919090; top:-17px; height:70%; padding:5px; solid rgb(200, 200, 200); '>
					<span style=""><br /><b style="position:relative; left:10px;"><a href="#" style="color:#333;" id="link">Body-Mass Index and mortality among Adults with Incident Type 2 Diabetes</a></b><br /><text style="position:relative; left:10px;">The relation between body weight and mortality among persons with type 2 diabetes remains unsolved..</text></span><br />
					<span style=" position:relative;left:10px;"><br /><b><a href="#" style="color:#333;" id="link">Young blood reverses age-related impairments in cognitive function and synaptic plasticity in mice</a></b><br />As human lifespan increases, a greater fraction of the population is suffering from age-related cognitive impairments, making it important to elucidate a means to combat the effects of aging. </span>
					<span style=" position:relative;left:90%;"><br /><b><a href="#" style="color:#333;" onclick="fullVersion()">More</a></b></span>
				</h5>
			</div>
			<div class='col-xs-6 content_box' style='background:none'>
				<h1 style='background:#424242; color:#F8F8F8; border-radius:10px;  border: 1px solid grey; margin-top:-10px; padding:5px;font-size:22px;'>&nbsp;&nbsp;Most popular literature <b style="font-size:12px; position:relative; left:73px;">Costs: 25</b> <img class='coins' style="position:relative; left:73px;" src='img/coins.png'></img></h1>
				<h5 style='background:white; position:relative; border-radius:10px; border:solid 1px; border-top-left-radius:0px; border-top-right-radius:0px; border-color:#919090; top:-17px; height:70%; padding:5px; solid rgb(200, 200, 200); '>
					<span style=""><br /><b style="position:relative; left:10px;"><a href="#" style="color:#333;" id="link">Body-Mass Index and mortality among Adults with Incident Type 2 Diabetes</a></b><br /><text style="position:relative; left:10px;">The relation between body weight and mortality among persons with type 2 diabetes remains unsolved..</text></span><br />
					<span style=" position:relative;left:10px;"><br /><b><a href="#" style="color:#333;" id="link">Young blood reverses age-related impairments in cognitive function and synaptic plasticity in mice</a></b><br />As human lifespan increases, a greater fraction of the population is suffering from age-related cognitive impairments, making it important to elucidate a means to combat the effects of aging. </span>
					<span style=" position:relative;left:90%;"><br /><b><a href="#" style="color:#464945;" onclick="fullVersion()">More</a></b></span><br />
				</h5>
			</div>
			<div class='col-xs-6 content_box' style='background:none'>
				<h1 style='background:#424242; color:#F8F8F8; border-radius:10px;  border: 1px solid grey; margin-top:-10px; padding:5px; font-size:22px;'>&nbsp;&nbsp;Your personal feed <b style="font-size:12px; position:relative; left:104px">Costs: 20</b> <img class='coins' style="position:relative; left:104px;" src='img/coins.png'></img></h1>
				<h5 style='background:white; position:relative; border-radius:10px; border:solid 1px; border-top-left-radius:0px; border-top-right-radius:0px;  border-color:#919090; top:-17px; height:70%; padding:5px; solid rgb(200, 200, 200);'>
					<span style=""><br /><b style="position:relative; left:10px;"><a href="#" style="color:#333;" id="link">Body-Mass Index and mortality among Adults with Incident Type 2 Diabetes</a></b><br /><text style="position:relative; left:10px;">The relation between body weight and mortality among persons with type 2 diabetes remains unsolved..</text></span><br />
					<span style=" position:relative;left:10px;"><br /><b><a href="#" style="color:#333;" id="link">Young blood reverses age-related impairments in cognitive function and synaptic plasticity in mice</a></b><br />As human lifespan increases, a greater fraction of the population is suffering from age-related cognitive impairments, making it important to elucidate a means to combat the effects of aging. </span>
					<span style=" position:relative;left:90%;"><br /><b><a href="#" style="color:#464945;" onclick="fullVersion()">More</b></span><br /></a>
				</h5>
			</div>
		</div>
	</div>
@stop

@section('sidebar')
	@parent
	@include('sidebarExtras')
@stop
