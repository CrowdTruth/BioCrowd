@extends('layout')

@section('content')
	<div class="section group" id="mainsection">
		<div class="col span_6_of_8" id="main">
			<div class="leaderboardBackground">
				<div>
					{{Form::open(['url' => 'leaderboard'])}}
					{{Form::select('type', array('20score' => 'Top 20 of scores', 'scoresday' => 'Top scores of today', 'scoresweek' => 'Top scores of the week', 'scoresmonth' => 'Top scores of this month','20judge' => 'Top 20 #judgements','judgeday' => 'Top #judgements of today','judgeweek' => 'Top  #judgements of the week','judgemonth' => 'Top  #judgements of the month'), '20scores')}}
					{{Form::close()}}
				</div>
				<div>
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
						@foreach ($rows as $row)
							<tr>
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