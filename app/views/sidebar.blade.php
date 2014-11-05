@section('sidebar')
	<div class="userinfo contentbox" style="margin-top:22px; margin-bottom:20px; width:320px; color:#333;">
			<div class="id_box"style="background:none;width:250px;height:100px;position:relative;left:20px;top:20px">
				<img src="img/BlankImage.png" style="width:90px;height:80px;position:relative;left:-30px;top:-10px;-moz-border-radius:9px; -webkit-border-radius:9px; border-radius:9px;"></img>
				<div class="player_info" style="width:170px;height:50px;position:relative;left:80px;top:-83px; font-size:14px;">
					<b>Dr. Wilson</b><br/>Level 1 Novice
					<hr style="margin-bottom:4px; margin-top:5px; border-top:1px solid grey" / >
					Score: 725
				</div>
				<img src="img/progress_bar.png" style="width:110%; height:15px; position:relative;top:-50px; left:-27px;"></img>
			</div>
		<br />
		<i>&nbsp;&nbsp;&nbsp;&nbsp;Complete 3 more games to level up</i>
		<br />
	</div>

	<div class="startplaying" style="width:320px; margin-top:-20px;">
		<a href="game_menu" style="color:black;"><button class='startButton'><b>Play games and help others</b><br /><span style="font-size:12px;">currently 10 people online</span></button></a>
	</div>
@show
