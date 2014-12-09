@extends('admin.layout')

@section('content')
	<h2>List all available games</h2>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Level</th>
				<th>#-tasks</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($games as $game)
			<tr>
				<td>{{ $game['type'] }}</td>
				<td>
					<a href="{{ URL::action('AdminController@editGameView', [ 'gameId' => $game['id'] ]) }}">{{ $game['name'] }}</a>
				</td>
				<td>{{ $game['level'] }}</td>
				<td>{{ $game['tasks'] }}</td>
			</tr>
		@endforeach
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<a class="btn btn-success" href="{{ URL::action('AdminController@editGameView', [ 'gameId' => 'null' ]) }}"><i class="fa fa-search fa-fw"></i>+ Add new</a>
				</td>
			</tr>
		</tbody>
	</table>
@stop
