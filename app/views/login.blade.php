@extends('layout') @section('extraheaders')
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
@stop

@section('content')

@if (Session::has('flash_error'))
	<font color='RED'>{{ Session::get('flash_error') }}</font>
@endif

<div class="container">
	<div class="row">
		<div class="span6" style="float: none; margin: 0 auto;">
			<div class="container-fluid" style="vertical-align: middle;">
				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse"
								data-parent="#accordion2" href="#collapseOne"> Login </a>
						</div>
						<div id="collapseOne" class="accordion-body collapse" style="height: 0px;">
							<div class="accordion-inner">
								{{ Form::open(array('url' => 'login', 'method' => 'POST')) }}
									<div>
										{{ Form::label('email:', 'Email:') }}
										{{ Form::text('email', Input::old('email')) }}
									</div>
									<div>
										{{ Form::label('password', 'Password') }}
										{{ Form::password('password') }}
									</div>
									<div>
										{{ Form::submit('Login') }}
									</div>
								{{ Form::close() }}
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse"
								data-parent="#accordion2" href="#collapseTwo"> Sign up </a>
						</div>
						<div id="collapseTwo" class="accordion-body collapse">
							<div class="accordion-inner">
								{{ Form::open(array('url' => 'register', 'method' => 'POST')) }}
									<div>
										{{ Form::label('email:', 'Email:') }}
										{{ Form::text('email', Input::old('email')) }}
									</div>
									<div>
										{{ Form::label('name:', 'Name:') }}
										{{ Form::text('name', Input::old('name')) }}
									</div>
									<div>
										{{ Form::label('password', 'Password') }}
										{{ Form::password('password') }}
									</div>
									<div>
										{{ Form::label('password2', 'Re-type password') }}
										{{ Form::password('password2') }}
									</div>
									<div>
										{{ Form::label('code', 'Invitation code') }}
										{{ Form::text('code', Input::old('code')) }}
									</div>
									<div>
										{{ Form::submit('Login') }}
									</div>
								{{ Form::close() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="jquery-2.0.2.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script>
	$(document).ready(function() {
			$('#collapseOne').collapse('show');
	});
</script>
@stop
