@extends('layout')

@section('extraheaders')
<script>
$(document).ready(function() {
	$("#leaderboardContentSelector").change(function() {
		window.location = this.value;
	});

	$(".leaderboardBackground").height($("#mainsection").height());
});
</script>
@stop

@section('content')
	<div class="section group" id="mainsection">
		@if(Auth::user()->check())
			<div class="col span_6_of_8" id="main">
		@else
			<div class="col span_8_of_8" id="main">
		@endif
			<div class="leaderboardBackground">
				<div>
					{{Form::open(['url' => 'leaderboard'])}}
					{{Form::select('type', array('leaderboard' => 'Top 20 scores of all time', 'scoresday' => 'Top 20 scores of today', 'scoresweek' => 'Top 20 scores of the week', 'scoresmonth' => 'Top 20 scores of the month','20judge' => 'Top 20 #judgements','judgeday' => 'Top 20 #judgements of today','judgeweek' => 'Top 20 #judgements of the week','judgemonth' => 'Top 20 #judgements of the month'), Route::getCurrentRoute()->getPath(), ['id'=>'leaderboardContentSelector', 'class'=>'btn btn-default dropdown-toggle', 'type'=>'button', 'data-toggle'=>'dropdown'])}}
					{{Form::close()}}
				</div>
				<div>
				<?php $userInfoIsOnPageAlready = false?>
					<table class="table table-striped">
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
							@if($setting == 'scores')
								Score
							@elseif($setting == 'judgements')
								#Judgements
							@endif
							</td>
						</tr>
						@if($rows != null)
							@foreach ($rows as $row)
								@if(is_object($row))
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
										@if($setting == 'scores')
											{{$row->score}}
										@elseif($setting == 'judgements')
											{{$row->nJudgements}}
										@endif
										</td>
									</tr>
								@else
									@if(Auth::user()->check() && ($row['user_id'] == Auth::user()->get()->id))
										<tr style="background-color: yellow;">
										<?php $userInfoIsOnPageAlready = true?>
									@else
										<tr>
									@endif
									<td>
										{{$row['currentRank']}}
										</td>
										<td>
										{{$row['name']}}
										</td>
										<td>
										{{$row['level']}}
										</td>
										<td>
										@if($setting == 'scores')
											{{$row['score']}}
										@elseif($setting == 'judgements')
											{{$row['nJudgements']}}
										@endif
										</td>
									</tr>
								@endif
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
						@else
							<tr style="background-color: yellow;">
								<td colspan="4">
									There are no entries for the chosen time period and rank type. 
								</td>
							</tr>
						@endif
						<!-- If the user is logged in, show the user's rank here if it's not in the top 20 already, If the user is in the top 20, highlight the user's row-->
						
					</table>
				</div>
			</div>
		</div>
		@if (Auth::user()->check())
		<!-- Begin sidebar -->
		@include('sidebar')
		<!-- End sidebar -->
		@endif
	</div>
@stop