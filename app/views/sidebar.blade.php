@section('sidebar')
	<div class="col span_2_of_8" id="sidebar">
		<div class="sidebarparent">
			<div id="minimize"><img src="img/glyphs/image_minimize_sidebar-01.png" width="50px"></img></div>
				<div id="level" class="sidebarchild" align="center">
					<div style="position: absolute; top: 14px; width:100%">
						<div style="font-size: 1.5em; color: white; left: 34.6%; text-align:center;">Level</div>
						<div style="font-size: 3em; color: white; left: 44.4%; text-align:center;">{{Auth::user()->get()->level}}</div>
					</div>
					<img src="img/glyphs/image_level.png" width="120px" id="levelimg"
							alt=""></img>
					<div style="position: absolute; top: 153px; width:100%">
						<div style="font-size: 1.5em; color: white; left: 34.6%; text-align:center;">Score</div>
						<div style="font-size: 3em; color: white; left: 44.4%; text-align:center;">{{Auth::user()->get()->score}}</div>
					</div>
					<img src="img/glyphs/image_score.png" width="120px" id="scoreimg"
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
						<strong>Campaigns</strong>
					</H1>
					<?php 
					//get the campaign progress for the campaigns this user has the highest progress of, 
					//and/or other running campaigns
					$userUnfinishedCampaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)
					->select('campaign_id','number_performed','times_finished')
					->where('times_finished','==','0') //only show campaigns that weren't finished yet by this user 
					->orderBy('number_performed','desc') //make sure we get to see the best progress bars by putting the highest number performed at the top. 
					->take(3)
					->get()->toArray();
					
					//make a variable to contain campaigns other then userCampainProgress contains
					$otherCampaigns = [];
					if(count($userUnfinishedCampaignProgress) >= 1){
						//make an array for all campaign_id's this user ever took part in
						$campaignProgressIds = [];
						//make an array of all campaign_id's this user ever took part in in the new variable $userCampaignProgress
						$userCampaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)
						->select('campaign_id','number_performed','times_finished')
						->orderBy('number_performed','desc') //make sure we get to see the best progress bars by putting the highest number performed at the top.
						->take(3)
						->get()->toArray();
						foreach($userCampaignProgress as $campaignProgress){
							//fill the campaignProgressId's array
							array_push($campaignProgressIds,$campaignProgress['campaign_id']);
						}
						//if the user doesn't have enough unfinished campaigns in the CampaignProgress table, get some campaigns out of the campaign table
						if(count($campaignProgressIds)>=1) {
							$otherCampaigns = Campaign::whereNotIn('id',$campaignProgressIds)
							->select('id','tag')
							->take(3-count($userUnfinishedCampaignProgress))
							->get()->toArray();
						}
					} else {
						//if the user has no unfinished campaigns in the CampaignProgress table, get 3 campaigns out of the campaign table
						$otherCampaigns = Campaign::select('id','tag')
						->take(3)
						->get()->toArray();
					}
					
					?>
					@foreach ($userUnfinishedCampaignProgress as $campaignProgress)
						<?php $campaignTag = Campaign::where('id',$campaignProgress['campaign_id'])->get()[0]->tag;
						$numberOfGamesInCampaign = count(CampaignGames::where('campaign_id',$campaignProgress['campaign_id'])->get()->toArray());
						?>
						<span>{{$campaignTag}}</span>
						<div id="progress">
							<div class="bar" style="width: {{($numberPerformed/$numberOfGamesInCampaign)*100}}%;">{{$campaignProgress['number_performed']}}/{{$numberOfGamesInCampaign}} completed</div>
						</div>
					@endforeach
					@foreach ($otherCampaigns as $sidebarCampaign)
						<?php $campaignTag = $sidebarCampaign['tag'];
						$campaignProgress = CampaignProgress::where('user_id',Auth::user()->get()->id)
						->where('campaign_id',$sidebarCampaign['id'])
						->get()->toArray();
						if(count($campaignProgress) == 0){
							//if the user has not made any progress with any capmaign, the numberPerformed is zero. 
							$numberPerformed = 0;
						} else {
							//Else, set the numberPerformed to the numberPerformed in the campaignProgress table for this user and this campaign_id. 
							$numberPerformed = $campaignProgress[0]['number_performed'];
						}
						$numberOfGamesInCampaign = count(CampaignGames::where('campaign_id',$sidebarCampaign['id'])->get()->toArray());
						?>
						<span>{{$campaignTag}}</span>
						<div id="progress">
							<div class="bar" style="width: {{($numberPerformed/$numberOfGamesInCampaign)*100}}%;">{{$numberPerformed}}/{{$numberOfGamesInCampaign}} completed</div>
						</div>
					@endforeach
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
								<h1><b>Leaderboard top 5: </b></h1> 
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
												{{Auth::user()->get()->score}}
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
								<a href="leaderboard"><button>Go to leaderboard</button></a>
							</div>
						</a>
					</div>
				</div>
			</div>

		</div>

	</div>
@show
