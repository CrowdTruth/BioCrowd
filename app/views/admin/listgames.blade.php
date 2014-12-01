@extends('admin.layout')

@section('content')
	<h2>List all available games</h2>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Id</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($games as $game)
			<tr>
				<td style="width:40%">{{ $game['id'] }}</td>
				<td style="width:40%">{{ $game['type'] }}</td>
				<td style="width:40%">Add tasks</td>
			</tr>
		@endforeach
			<tr>
				<td style="width:40%"></td>
				<td style="width:40%">Add new ??</td>
				<td style="width:40%"></td>
			</tr>
		</tbody>
	</table>
@stop
