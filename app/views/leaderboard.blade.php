@extends('layout')

@section('content')
	<div class="section group" id="mainsection">
		<div class="col span_6_of_8" id="main">
			<div class="leaderboardBackground">
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
								{{$row->rank}}
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
						<!-- If the user is logged in, show the user's rank here if it's not in the top 20 already -->
						
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