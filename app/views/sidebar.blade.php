@section('sidebar')
	<div class="col span_2_of_8" id="sidebar">
		<div class="sidebarparent">
			<div id="minimize"><img src="img/glyphs/image_minimize_sidebar-01.png" width="50px"></img></div>
				<div id="level" class="sidebarchild" align="center">
				<div style="position: relative"><div style="font-size: 2em; color: white; position: absolute; top: 20px; left: 34.6%;">Level</div><div style="font-size: 4em; color: white; position: absolute; top: 45px; left: 44.4%;">{{Auth::user()->get()->level}}</div></div>
					<img src="img/glyphs/image_level.png" width="120px" id="levelimg"
							alt=""></img>
					<div id="progress">
						<?php 
						$userScore = Auth::user()->get()->score;
						$userLevel = Auth::user()->get()->level;
						//This is to ensure that max_score is never null
						$highestLevel = Level::orderBy('level', 'desc')->first(['level'])['level'];
						if($userLevel > $highestLevel){
							$max_score = $userScore;
						} else {
							$max_score = Level::where('level', $userLevel)->first(['max_score'])['max_score'];
						}
						$previous_max_score = Level::where('level', $userLevel-1)->first(['max_score'])['max_score'];
						$percentage = round((($userScore-$previous_max_score)/($max_score-$previous_max_score))*100);?>
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
				
				<div id="sidebarLeaderboard" class="sidebarchild" align="center">
					<?php
					$rowsAndRank = App::make("LeaderboardController")->sidebarLeaderboard();
					//$rowsAndRank = LeaderboardController::sidebarLeaderboard();
					$rows = $rowsAndRank[0];
					$userRank = $rowsAndRank[1];?>
					<div>
						<a href="leaderboard">
							<div>
								<b>Leaderboard top 5: </b> 
								<?php $userInfoIsOnPageAlready = false?>
								<table>
									<tr>
										<td>
										Rank
										</td>
										<td>
										Name
										</td>
										<td>
										Level
										</td>
										<td>
										Score
										</td>
									</tr>
									@if($rows != null)
										@foreach ($rows as $row)
											@if(Auth::user()->check() && ($row->user_id == Auth::user()->get()->id))
												<tr style="background-color: yellow;">
												<?php $userInfoIsOnPageAlready = true?>
											@else
												<tr>
											@endif
												<td>
												{{$row->currentRank}}
												</td>
												<td>
												{{$row->name}}
												</td>
												<td>
												{{$row->level}}
												</td>
												<td>
												{{$row->score}}
												</td>
											</tr>
										@endforeach
										@if(Auth::user()->check() && !$userInfoIsOnPageAlready && $userRank != '')
											<tr style="background-color: yellow;">
												<td>
												{{$userRank}}
												</td>
												<td>
												{{Auth::user()->get()->name}}
												</td>
												<td>
												{{Auth::user()->get()->level}}
												</td>
												<td>
												@if($setting == 'scores')
													{{Auth::user()->get()->score}}
												@elseif($setting == 'judgements')
													{{$userNJudgements}}
												@endif
												</td>
											</tr>
										@elseif(Auth::user()->check() && !$userInfoIsOnPageAlready && $userRank == '')
											<tr style="background-color: yellow;">
												<td colspan="4">You don't have a rank yet. Play a game to earn your place in the leaderboard! </td>
											</tr>
										@endif
									@endif
									<!-- If the user is logged in, show the user's rank here if it's not in the top 20 already, If the user is in the top 20, highlight the user's row-->
								</table>
							</div>
						</a>
					</div>
				</div>
			</div>

		</div>

	</div>
@show
