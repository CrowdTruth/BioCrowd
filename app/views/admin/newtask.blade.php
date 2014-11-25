@extends('admin.layout')

@section('content')
	<h2>Create your new task here!</h2>

	<div class="panel-body">
		{{ Form::open( [ 'action' => 'AdminController@newTaskAction', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' ] ) }}
			<div class="form-group">
				{{ Form::label('taskType', 'Task Type:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-xs-3">
					{{ Form::select('taskType', $taskTypes, null, [ 'class' => 'form-control' ]) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('data', 'Data:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-xs-3">
					{{ form::text('data', '',  [ 'class' => 'form-control' ] ) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					{{ Form::submit('Create task') }}
				</div>
			</div>
		{{ Form::close() }}
	</div>	
@stop


