@extends('layout')

@section('content')
	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion"
						href="#collapseOne"> Login </a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
					{{ Form::open(array('url' => 'login', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form')) }}
						<div class="form-group">
							{{ Form::label('email', 'Email:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
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
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion"
						href="#collapseTwo"> Register </a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
				<div class="panel-body">
					{{ Form::open(array('url' => 'register', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form')) }}
						<div class="form-group">
							{{ Form::label('email', 'Email:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('name', 'Name:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('password', 'Password:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::password('password', array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('password2', 'Re-type password:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::password('password2', array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('code', 'Invitation code:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::text('code', Input::old('code'), array('class' => 'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('bioExpert', 'Are you a biological expert?', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::label('yes', 'Yes') }}
								{{ Form::radio('bioExpert', '1', false, ['required'=>'required']) }}
								{{ Form::label('no', 'No') }}
								{{ Form::radio('bioExpert', '0', false, ['required'=>'required']) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('expertise', 'Field of expertise:', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::text('expertise', '', ['required'=>'required']) }}
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-4">
								{{ Form::submit('Login') }}
							</div>
						</div>
						{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@stop
