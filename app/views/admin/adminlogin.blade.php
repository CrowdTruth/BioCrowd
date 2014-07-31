@extends('adminlayout')

@section('content')
	<div class="panel-body">
		{{ Form::open(array('action' => 'AdminController@doLogin', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form')) }}
			<div class="form-group">
				{{ Form::label('username', 'Username:', array('class' => 'col-sm-2 control-label')) }}
				<div class="col-sm-4">
					{{ Form::text('username', Input::old('username'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('password', 'Password:', array('class' => 'col-sm-2 control-label')) }}
				<div class="col-sm-4">
					{{ Form::password('password', array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					{{ Form::submit('Login') }}
				</div>
			</div>
		{{ Form::close() }}
	</div>
@stop
