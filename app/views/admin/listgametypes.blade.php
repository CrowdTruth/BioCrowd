@extends('admin.layout')

@section('content')
	<h2>Available game types</h2>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Game name</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($gameTypes as $gameType)
			<tr>
				<td style="width:40%">{{ $gameType['name'] }}</td>
				<td>
				@if($gameType['installed'])
					Installed
				@else
					<a class="btn btn-success" href="{{ URL::action('AdminController@listGameTypesAction', [ 'handler' => $gameType['handledFile'] ]) }}"><i class="fa fa-search fa-fw"></i>Install</a>
				@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop
