@if (Auth::user()->check())
	<div class="section group" id="banner" align='center'>
		<div class="col span_3_of_8">
			<a href="{{ Lang::get('gamelabels.gameUrl') }}" style="text-decoration: none;" ><div id="gameLogo" width="400px">{{ Lang::get('gamelabels.logoText') }}</div></a>
		</div>
		<div class="col span_5_of_8">
					<a href="#"><img src="img/glyphs/logo_twitter.png" height=45px></img></a>
					<a href="#"><img src="img/glyphs/logo_facebook.png" height=45px></img></a>
					<a href="profile"><img src="img/BlankImage.png" height="45px"></img></a>
					<a id="userNameInBanner" href="profile"><span>{{ Auth::user()->get()->name }}</span></a>				
					<div class="sidebarbutton" align="right">
						<img src="img/glyphs/arrow.png" height="20px"></img>
					</div>

		</div>
	</div>
@endif
@if (!Auth::user()->check())
	<div class="section group" id="banner" align='center'>
		<div class="col span_4_of_8">
			<a href="{{ Lang::get('gamelabels.gameUrl') }}"><img src="img/logo/logo_games-02.png" width="400px"></a>
		</div>
	
		<div class="col span_4_of_8" >
			<div id="bannersocial" align="center">
				<a href="#"><img src="img/glyphs/logo_twitter.png" height=45px></img></a>
				<a href="#"><img src="img/glyphs/logo_facebook.png" height=45px></img></a>
			</div>
			<div id="bannerlogin" >
			<input class="button login" id="login" name="" type="button" value="Login"/>
				<div class="popup login triangle-isosceles top" align="center">
					<div align="right" class="nodeclink2">
						<a href="#" class="close " style="">X</a>
					</div>
					{{ Form::open(array('url' => 'login', 'method' => 'POST', 'role' => 'form')) }}
						<span>Log in to start the fun.</span>
						<div id="loginform">
							<table class="formtable">
								<tr>
									<td><strong>{{ Form::label('email', 'Email:') }}</strong></td>
									<td>{{ Form::text('email', Input::old('email'), array('placeholder' => 'Email')) }}</td>
								</tr>
								<tr>
									<td><strong>{{ Form::label('password', 'Password:') }}</strong></td>
									<td>{{ Form::password('password', array('placeholder' => 'Password')) }}</td>
								</tr>
							</table>
						</div>
						<P>
							{{ Form::submit('Login', array('class' => 'formbutton')) }}
						</P>
					{{ Form::close() }}
				</div>
				<input class="button signup" id="signup" name="" type="button" value="Sign up"/>
	
	
				<div class="popup signup triangle-isosceles top" align="center">
					<div align="right" class="nodeclink2">
						<a href="#" class="close " style="">X</a>
					</div>
					{{ Form::open(array('url' => 'register', 'method' => 'POST', 'role' => 'form')) }}
					<span>Sign up, it's easy.</span>
					<div id="signupform" align="center">
						<table class="formtable">
							<tr>
								<td><strong>{{ Form::label('email', 'Email:') }}</strong></td>
								<td>{{ Form::text('email', Input::old('email'), array('placeholder' => 'Email')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('name', 'Name:') }}</strong></td>
								<td>{{ Form::text('name', Input::old('name'), array('placeholder' => 'Name')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('password', 'Password:') }}</strong></td>
								<td>{{ Form::password('password', array('placeholder' => 'Password')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('password2', 'Re-type password:') }}</strong></td>
								<td>{{ Form::password('password2', array('placeholder' => 'Re-type password')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('code', 'Invitation code:') }}</strong></td>
								<td>{{ Form::text('code', Input::old('code'), array('placeholder' => 'Invitation code')) }}</td>
							</tr>
							<tr>
								<td colspan='2'><strong>{{ Form::label('bioExpert', 'What is the highest level of cell biology education you have finished?') }}</strong></td>
							</tr>
							<tr>
								<td colspan='2'>{{ Form::radio('cellBioExpertise', 'none', false, ['required'=>'required', 'id'=>'none']) }}
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
								{{ Form::label('phd', 'Phd', ['id'=>'phd']) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('expertise', 'Field of expertise:') }}</strong></td>
								<td>{{ Form::text('expertise', '', ['placeholder' => 'Expertise', 'required'=>'required']) }}</td>
							</tr>
						</table>
					</div>
					<P>
						{{ Form::submit('Login', array('class' => 'formbutton')) }}
					</P>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endif
