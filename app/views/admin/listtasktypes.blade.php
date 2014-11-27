@extends('admin.layout')

@section('content')
	<h2>List all available task types</h2>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Task name</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($taskTypes as $taskType)
			<tr>
				<td style="width:40%">{{ $taskType['name'] }}</td>
				<td>
				@if($taskType['installed'])
					Installed
				@else
					<a class="btn btn-success" href="{{ URL::action('AdminController@listTaskTypeAction', [ 'handler' => $taskType['handledFile'] ]) }}"><i class="fa fa-search fa-fw"></i>Install</a>
				@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@stop
