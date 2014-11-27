@extends('admin.layout')

@section('content')
	<h2>List all available tasks</h2>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Id</th>
				<th>Type</th>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($tasks as $task)
			<tr>
				<td style="width:40%">{{ $task['id'] }}</td>
				<td style="width:40%">{{ $task['type'] }}</td>
				<td style="width:40%">{{ $task['data'] }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop
