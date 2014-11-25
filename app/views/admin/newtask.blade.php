@extends('admin.layout')

@section('content')
	<h2>Create your new task here!</h2>
	
	We need:
	<ul>
		<li>task_type - Ref task_types</li>
		<li>data</li>

			$table->string('data');
			$table->longText('response');
	
	</ul>

	<div class="panel-body">
		{{ Form::open( [ 'action' => 'AdminController@newTaskAction', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' ] ) }}
			<div class="form-group">
				{{ Form::label('username', 'Username:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
					{{ Form::text('username', Input::old('username'),  [ 'class' => 'form-control' ] ) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('password', 'Password:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
					{{ Form::password('password', [ 'class' => 'form-control' ] ) }}
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
