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
							{{ Form::label('bioExpert', 'What is the highest level of cell biology education you have finished?', array('class' => 'col-sm-2 control-label')) }}
							<div class="col-sm-4">
								{{ Form::radio('cellBioExpertise', 'none', false, ['required'=>'required', 'id'=>'none']) }}
								{{ Form::label('none', 'None', ['id'=>'none']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'highSchool', false, ['required'=>'required', 'id'=>'highSchool']) }}
								{{ Form::label('highSchool', 'High school', ['id'=>'highSchool']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'hboBachelor', false, ['required'=>'required', 'id'=>'hboBachelor']) }}
								{{ Form::label('hboBachelor', 'Pre-university/hbo-bachelor (Dutch)', ['id'=>'hboBachelor']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'uniBachelor', false, ['required'=>'required', 'id'=>'uniBachelor']) }}
								{{ Form::label('uniBachelor', 'University bachelor', ['id'=>'uniBachelor']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'hboMaster', false, ['required'=>'required', 'id'=>'hboMaster']) }}
								{{ Form::label('hboMaster', 'Pre-university/hbo master (Dutch)', ['id'=>'hboMaster']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'uniMaster', false, ['required'=>'required', 'id'=>'uniMaster']) }}
								{{ Form::label('uniMaster', 'University Master', ['id'=>'uniMaster']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'phd', false, ['required'=>'required', 'id'=>'phd']) }}
								{{ Form::label('phd', 'Phd', ['id'=>'phd']) }}
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
