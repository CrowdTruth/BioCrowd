@section('sidebar')
	<div class="col span_2_of_8" id="sidebar">
		<div class="sidebarparent">
			<div id="minimize"><img src="img/glyphs/image_minimize_sidebar-01.png" width="50px"></img></div>
				<div id="level" class="sidebarchild" align="center">
				<div style="position: relative"><div style="font-size: 4em; color: white; position: absolute; top: 30px; left: 100px;">{{Auth::user()->get()->level}}</div></div>
					<img src="img/glyphs/image_level.png" width="120px" id="levelimg"
							alt=""></img>
					<div id="progress">
						<?php 
						$userScore = Auth::user()->get()->score;
						$userLevel = Auth::user()->get()->level;
						$max_score = Level::where('level', $userLevel)->first(['max_score'])['max_score'];
						$percentage = round(($userScore/$max_score)*100);?>
						<div class="bar" style="width: {{$percentage}}%;">
						{{$percentage}}%</div>
					</div>

					<span>Complete {{round(($max_score-$userScore)/Game::orderBy('score', 'desc')->where('level', '<=', $userLevel)->first(['score'])['score'])}} more high-level games to
							level up</span>
				</div>
				<div id="achievements" class="sidebarchild" align="center">
					<H1>
						<strong>Achievements</strong>
					</H1>
					<span>Army campaign</span>
					<div id="progress">
						<div class="bar" style="width: 60%;">3/5 completed</div>
					</div>
					<span>5 games</span>
					<div id="progress">
						<div class="bar" style="width: 60%;">3/5 completed</div>
					</div>
					<span>10 games</span>
					<div id="progress">
						<div class="bar" style="width: 80%;">8/10 completed</div>
					</div>
					<button>See all campaigns</button>
				</div>

				<div id="score" class="sidebarchild" align="center">
					<table>
						<tr>
							<td><img src="img/glyphs/scorecompare-02.png" width="90%"></img></td>
							<td><span >+2</span></td>
							<td><img src="img/glyphs/scorecompare-03.png" width="90%"></img></td>
						</tr>
					</table>
					<div id="claimcontainer">
					<div id="claimbutton"><img src="img/glyphs/scorecompare-04.png" width="25px"></img><span>Claim High Urgency Literature</span></div>
					<div id="claimbutton"><img src="img/glyphs/scorecompare-05.png" width="25px"></img><span>Claim New Literature</span></div>
					<div id="claimbutton"><img src="img/glyphs/scorecompare-05.png" width="25px"></img><span>Claim Most Popular Literature</span></div>
				</div>
			</div>

		</div>

	</div>
@show
