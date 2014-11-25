@extends('admin.layout')

@section('content')
	<div class="panel-body">
		{{ Form::open([ 'action' => 'AdminController@doLogin', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' ] ) }}
			<div class="form-group">
				{{ Form::label('username', 'Username:', [ 'class' => 'col-sm-2 control-label' ] ) }}
				<div class="col-sm-4">
					{{ Form::text('username', Input::old('username'), [ 'class' => 'form-control' ] ) }}
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
					{{ Form::submit('Login') }}
				</div>
			</div>
		{{ Form::close() }}
	</div>
@stop
