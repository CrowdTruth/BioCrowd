<html>
<body>
	<h1>TEMP LOGIN</h1>
	<p>Developer note: Perhaps this should EXTEND the base layout (blade
		layout) ?</p>

	<hr>
	@if (Session::has('flash_error'))
		<font color='RED'>{{ Session::get('flash_error') }}</font> 
	@endif

	<div class="container">
		{{ Form::open(array('url' => 'login', 'method' => 'POST')) }}
		<h2 class="">Please sign in</h2>
		<div class="control-group ">
			{{ Form::label('email:', 'Email:') }}
			<div class="controls">{{ Form::text('email', Input::old('email')) }}</div>
		</div>
		<div class="control-group ">
			{{ Form::label('password', 'Password') }}
			<div class="controls">{{ Form::password('password') }}</div>
		</div>
		{{ Form::submit('Login') }} {{ Form::close() }}
	</div>
</body>
</html>
