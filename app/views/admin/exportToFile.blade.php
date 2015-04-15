@extends('admin.layout')

@section('content')
<h2>Export to file</h2>
{{ Form::open([ 'action' => 'DataportController@exportToFileView', 'name' => 'annotationForm' ]) }}
<table class="table table-striped">
	<thead>
		<tr>
			<th>{{ Form::checkbox('checkAll', 'None', false, ['id' => 'checkAll']) }}</th>
			<th>Game name</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($games as $game)
		<tr>
			<td>{{ Form::checkbox('games[]', $game['id'], false, [ 'class' => 'check' ]) }}</td>
			<td>{{ $game['name'] }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
{{ Form::submit('Export to CSV') }}
{{ Form::close() }}

<script>
	$("#checkAll").click(function () {
		$(".check").prop('checked', $(this).prop('checked'));
	});
</script>

@stop

