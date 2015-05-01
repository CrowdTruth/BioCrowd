@extends('admin.layout')

@section('content')
	<h2>Available games</h2>

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
					<a href="{{ URL::action('GameAdminController@getEditGame', [ 'gameId' => $game['id'] ]) }}">{{ $game['name'] }}</a>
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
					<a class="btn btn-success" href="{{ URL::action('GameAdminController@getEditGame', [ 'gameId' => 'null' ]) }}"><i class="fa fa-search fa-fw"></i>+ Add new</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>
				{{ Form::open([ 'action' => 'GameAdminController@postGameUpload', 'id' => 'fileform', 'files' => true ]) }}
				{{ Form::file('csvfile', [ 'class' => 'btn btn-success', 'onchange' => 'upload(event)' ] ) }}
				{{ Form::close() }}
				</td>
			</tr>
		</tbody>
	</table>

@stop

@section('body-javascript')
	<script>
		function upload(event) {
			$('#fileform').submit();
		}
	</script>
@stop
