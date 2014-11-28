@extends('admin.layout')

@section('extraheaders')
<script>
	function changeDataDiv(taskType) {
		divHtml = '';
		switch(taskType) {
		@foreach($taskTypesDivs as $id => $div)
			case "{{ $id }}": divHtml = "{{ $div }}"; break;
		@endforeach
		}
		$('#data').html(divHtml);
	}
</script>
@stop

@section('content')
	<h2>Create your new task here!</h2>

	<div class="panel-body">
		{{ Form::open( [ 'action' => 'AdminController@newTaskAction', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' ] ) }}
			<div class="form-group">
				{{ Form::label('taskType', 'Task Type:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-xs-3">
					{{ Form::select('taskType', $taskTypesNames, null, [ 'class' => 'form-control' ]) }}
				</div>
			</div>
			<div class="form-group" id="data">
			<!-- This DIV should be filled depending on selected task type -->
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					{{ Form::submit('Create task') }}
				</div>
			</div>
		{{ Form::close() }}
	</div>	
@stop

@section('body-javascript')
<script>
	$(document).ready(function(){
		$("#taskType").change(function(){
			changeDataDiv($("#taskType").val());
		});
		$("#taskType").change();
	});
</script>
@stop
