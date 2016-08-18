<div class="section group" id="banner" align='center'>
	<div class="span_8_of_8">
		<div id='logoleft'>
			<a href="{{ Lang::get('gamelabels.gameUrl') }}" style="text-decoration: none; " ><div id="gameLogo" width="400">{{ Lang::get('gamelabels.logoText') }}</div></a>
		</div>
		<div style='float:right; margin-right:5px; margin-top:5px;'>
		@if (Auth::user()->check())
			<div id="bannerBackButtonContainer" style="position: relative; display: inline-block;">
				<button id="bannerBackButton" onclick="history.go(-1);">Back </button>
			</div>
			<div style="position: relative; display: inline-block;">
				<div style="display: inline-block;">
					<a href="profile"><img id="userPictureInBannerButton" src="img/BlankImage.png" height="45px"></img></a>
					<a id="userNameInBanner" href="profile"><span id="userNameInBannerButton">{{ Auth::user()->get()->name }}</span></a>
					<div id="levelAndBadgeInBanner" style="position: relative; display: inline-block;">
						<div>
							Level {{ Auth::user()->get()->level }}
						</div>
						<div>
						<?php 
						$userHasBadge = UserHasBadge::where("user_id",Auth::user()->get()->id)->orderBy("updated_at",'DESC')->first();
						if(count($userHasBadge)>0){
							$badge = Badge::where('id',$userHasBadge->badge_id)->get()[0];
						}
						?>
						@if (isset($badge))
							{{$badge->text}}
						@endif
						</div>
					</div>
					<div style="position: relative; display: inline-block;">
						<div id="campaignsIconInBanner" title="# of campaigns finished. Click to see an overview of all campaigns">
							<div id="campaignCountDropDownText" style="font-size: 8px; position:absolute; top:19px; text-align: center; width: 100%; padding-left:3px;">Campaigns</div>
							<div id="campaignCountDropDown" style="position:absolute; text-align: center; width: 100%; top:27px; padding-left:3px;"> {{count(CampaignProgress::where('user_id',Auth::user()->get()->id)->where('times_finished','>','0')->get()->toArray())}}</div>
							<div><img height="45px" style="padding-left:7px;" src="img/glyphs/yellow_hexagon.png"></div>
							<div id="campaignDropDowns" style="text-align: center;">
							<?php $playedCampaignTags = CampaignProgress::where('user_id',Auth::user()->get()->id)->select('campaign_id')->orderBy('campaign_id')->get(); ?>
							<?php $playedCampaignArray = []?>
							<!-- Check if the user has played any campaigns -->
							@if(count($playedCampaignTags)>0)
								<!-- If the user has played campaigns -->
								@foreach($playedCampaignTags as $playedCampaignTag)
									<!-- Loop through the campaigns and show them in the dropdown. Also fill an array with the game id's -->
									<?php $playedCampaign = Campaign::where("id",$playedCampaignTag["campaign_id"])->get()?>
									@if(count($playedCampaign)>0)
										<a href="playCampaign?campaignId={{$playedCampaignTag["campaign_id"]}}"><div class="dropdownItem playedItem"> <img id="campaignDropDown {{$playedCampaign[0]["tag"]}}" width=100%" src="{{$playedCampaign[0]["image"]}}" title="{{$playedCampaign[0]["tag"]}}" style="padding-left:0px;"></div></a>
										<?php array_push($playedCampaignArray,$playedCampaignTag["campaign_id"]);?>
									@endif
								@endforeach
								<!-- When all played campaigns are done cycling, get the unplayed campaigns -->
							<?php $unplayedCampaignTags = Campaign::whereNotIn('id',$playedCampaignArray)->get();?>
								@foreach($unplayedCampaignTags as $unplayedCampaignTag)
								<!-- loop through the unplayed campaigns and show them in the dropdown to show all campaigns in total -->
									<a href="playCampaign?campaignId={{$unplayedCampaignTag["id"]}}"><div class="dropdownItem unplayedItem"><img id="campaignDropDown {{$unplayedCampaignTag["tag"]}}" width=100%" src="{{$unplayedCampaignTag["image"]}}" title="{{$unplayedCampaignTag["tag"]}}" style="padding-left:0px;"></div></a>
								@endforeach
							@else
								<!-- If the user hasn't played any campaigns, just show all campaigns in the dropdown as unplayed. -->
								<?php $unplayedCampaignTags = Campaign::all();?>
								@foreach($unplayedCampaignTags as $unplayedCampaignTag)
									<?php $unplayedCampaign = Campaign::where("id",$unplayedCampaignTag["id"])->get();?>
									@if(count($unplayedCampaign)>0)
										<a href="playCampaign?campaignId={{$unplayedCampaignTag["id"]}}"><div class="dropdownItem unplayedItem"><img id="campaignDropDown {{$unplayedCampaignTag["tag"]}}" width=100%" src="{{$unplayedCampaignTag["image"]}}" title="{{$unplayedCampaignTag["tag"]}}" style="padding-left:0px;"></div></a>
									@endif
								@endforeach
							@endif
							</div>
						</div>
					</div>
					<div style="position: relative; display: inline-block;">
						<div id="gamesIconInBanner" title="# of games finished. Click to see an overview of all games">
							<div id="gameCountDropDownText" style="font-size: 8px; position:absolute; top:19px; text-align: center; width: 100%; padding-left:3px;">Games</div>
							<div id="gameCountDropDown" style="position:absolute; text-align: center; width: 100%; top:27px; padding-left:3px;"> {{count(Judgement::distinct()->select('created_at')->where('user_id',Auth::user()->get()->id)->where('flag','!=','incomplete')->where('flag','!=','skipped')->get()->toArray())}}</div>
							<div><img height="45px" style="padding-left:7px;" src="img/glyphs/lightgreen_hexagon.png"></div>
							<div id="gameDropDowns" style="text-align: center;">
							<?php $playedGameTags = Judgement::distinct()->select('created_at')->where('user_id',Auth::user()->get()->id)->where('flag','!=','incomplete')->where('flag','!=','skipped')->select('game_id')->get(); ?>
							<?php $playedGameArray = []?>
							<!-- Check if the user has played any games -->
							@if(count($playedGameTags)>0)
								<!-- If the user has played games -->
								@foreach($playedGameTags as $playedGameTag)
									<!-- Loop through the games and show them in the dropdown. Also fill an array with the game id's -->
									<?php $playedGame = Game::where("id",$playedGameTag["game_id"])->get();?>
									@if(count($playedGame)>0)
										<?php $playedGameType = GameType::where('id',$playedGame[0]->game_type_id)->get();?>
										<a href="playGame?gameId={{$playedGameTag["game_id"]}}"><div class="dropdownItem playedItem"> <img id="gameDropDown {{$playedGame[0]["tag"]}}" width=100%" src="{{$playedGameType[0]["thumbnail"]}}" title="{{$playedGame[0]["tag"]}}" style="padding-left:0px;"></div></a>
										<?php array_push($playedGameArray,$playedGameTag["game_id"]);?>
									@endif
								@endforeach
								<!-- When all played games are done cycling, get the unplayed games -->
								<?php $unplayedGameTags = Game::whereNotIn('id',$playedGameArray)->get();?>
								@foreach($unplayedGameTags as $unplayedGameTag)
									<!-- loop through the unplayed games and show them in the dropdown to show all games in total -->
									<?php $unplayedGame = Game::where("id",$unplayedGameTag["id"])->get();?>
									@if(count($unplayedGame)>0)
										<?php $unplayedGameType = GameType::where('id',$unplayedGame[0]->game_type_id)->get();?>
										<a href="playGame?gameId={{$unplayedGameTag["id"]}}"><div class="dropdownItem unplayedItem"><img id="gameDropDown {{$unplayedGameTag["tag"]}}" width=100%" src="{{$unplayedGameType[0]["thumbnail"]}}" title="{{$unplayedGameTag["tag"]}}" style="padding-left:0px;"></div></a>
									@endif
								@endforeach
							@else
								<!-- If the user hasn't played any games, just show all games in the dropdown as unplayed. Get the icons from the gametypes table. -->
								<?php $unplayedGameTags = Game::all();?>
								@foreach($unplayedGameTags as $unplayedGameTag)
									<?php $unplayedGame = Game::where("id",$unplayedGameTag["id"])->get();?>
									@if(count($unplayedGame)>0)
										<?php $unplayedGameType = GameType::where('id',$unplayedGame[0]->game_type_id)->get();?>
										<a href="playGame?gameId={{$unplayedGameTag["id"]}}"><div class="dropdownItem unplayedItem"><img id="gameDropDown {{$unplayedGameTag["tag"]}}" width=100%" src="{{$unplayedGameType[0]["thumbnail"]}}" title="{{$unplayedGameTag["tag"]}}" style="padding-left:0px;"></div></a>
									@endif
								@endforeach
							@endif
							</div>
						</div>
					</div>
					<div class="sidebarbutton" align="right">
						<img id="sidebarArrow" src="img/glyphs/arrow.png" height="20px"></img>
					</div>
				</div>
			</div>
		@endif
		@if (!Auth::user()->check())
			<div id="bannerlogin">
				<input class="button login" id="login" name="" type="button" value="Login"/>
				<div class="popup login login_triangle top" align="center">
					<div align="right" class="nodeclink2">
						<a href="#" class="close " style="">X</a>
					</div>
					{{ Form::open(array('url' => 'login', 'method' => 'POST', 'role' => 'form')) }}
						<span>Log in to start the fun.</span>
						<div id="loginform">
							<table class="formtable">
								<tr>
									<td><strong>{{ Form::label('email', 'Email:') }}</strong></td>
									<td>{{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'loginSignupFields')) }}</td>
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
	
	
				<div class="popup signup signup_triangle top" align="center">
					<div align="right" class="nodeclink2">
						<a href="#" class="close " style="">X</a>
					</div>
					{{ Form::open(array('url' => 'register', 'method' => 'POST', 'role' => 'form')) }}
					<span>Sign up, it's easy.</span>
					<div id="signupform" align="center">
						<table class="formtable">
							<tr>
								<td><strong>{{ Form::label('email', 'Email:') }}</strong></td>
								<td><strong>*</strong></td>
								<td>{{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'required' => 'required', 'class' => 'loginSignupFields')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('name', 'Name:') }}</strong></td>
								<td><strong>*</strong></td>
								<td>{{ Form::text('name', Input::old('name'), array('placeholder' => 'Name', 'required' => 'required', 'class' => 'loginSignupFields')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('password', 'Password:') }}</strong></td>
								<td><strong>*</strong></td>
								<td>{{ Form::password('password', array('placeholder' => 'Password', 'required' => 'required'))}}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('password2', 'Re-type password:') }}</strong></td>
								<td><strong>*</strong></td>
								<td>{{ Form::password('password2', array('placeholder' => 'Re-type password', 'required' => 'required')) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('code', 'Invitation code:') }}</strong></td>
								<td><strong>*</strong></td>
								<td>{{ Form::text('code', Input::old('code'), array('placeholder' => 'Invitation code', 'required' => 'required', 'class' => 'loginSignupFields')) }}</td>
							</tr>
							<tr>
								<td colspan='3'><strong>{{ Form::label('bioExpert', 'What is the highest level of cell biology education you have finished?') }}</strong></td>
							</tr>
							<tr>
								<td colspan='3'>{{ Form::radio('cellBioExpertise', 'none', false, ['id'=>'none']) }}
								{{ Form::label('none', 'None', ['id'=>'none']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'highSchool', false, ['id'=>'highSchool']) }}
								{{ Form::label('highSchool', 'High school', ['id'=>'highSchool']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'hboBachelor', false, ['id'=>'hboBachelor']) }}
								{{ Form::label('hboBachelor', 'Pre-university/hbo-bachelor (Dutch)', ['id'=>'hboBachelor']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'uniBachelor', false, ['id'=>'uniBachelor']) }}
								{{ Form::label('uniBachelor', 'University bachelor', ['id'=>'uniBachelor']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'hboMaster', false, ['id'=>'hboMaster']) }}
								{{ Form::label('hboMaster', 'Pre-university/hbo master (Dutch)', ['id'=>'hboMaster']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'uniMaster', false, ['id'=>'uniMaster']) }}
								{{ Form::label('uniMaster', 'University Master', ['id'=>'uniMaster']) }} <BR>
								{{ Form::radio('cellBioExpertise', 'phd', false, ['id'=>'phd']) }}
								{{ Form::label('phd', 'Phd', ['id'=>'phd']) }}</td>
							</tr>
							<tr>
								<td><strong>{{ Form::label('expertise', 'Field of expertise:') }}</strong></td>
								<td></td>
								<td>{{ Form::text('expertise', '', ['placeholder' => 'Expertise', 'class' => 'loginSignupFields']) }}</td>
							</tr>
						</table>
					</div>
					<P>
						{{ Form::submit('Login', array('class' => 'formbutton')) }}
					</P>
					{{ Form::close() }}
				</div>
			</div>
		@endif
		</div>
	</div>
</div>
@if((Auth::user()->check()) && (Auth::user()->get()->guest_user == true))
	<div id="guestUserBanner">
		<div>
			<div class="guestUserBannerText">
				You are currently logged in as a guest user. To make your account permanent click <a id="expandGuestAccountForm">here</a>
			</div>
			<div id="closeGuestUserMessageButton" style="float: right">X</div>
		</div>
		<div id="guestAccountForm">
			{{ Form::open(['url' => 'convertGuestToUser']) }}
				<table>
					<tr>
						<td>E-mail</td>
						<td>{{ Form::text('email', '', ['required' => 'required']) }}</td>
					</tr>
					<tr>
						<td>Username</td>
						<td>{{ Form::text('name', '', ['required' => 'required']) }}</td>
					</tr>
					<tr>
						<td>Password</td>
						<td>{{ Form::password('password', array('placeholder' => 'Password')) }}</td>
					</tr>
					<tr>
						<td>What is the highest level of cell biology education you have finished?</td>
						<td>{{ Form::radio('cellBioExpertise', 'none', false, ['id'=>'none']) }}
							{{ Form::label('none', 'None', ['id'=>'none']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'highSchool', false, ['id'=>'highSchool']) }}
							{{ Form::label('highSchool', 'High school', ['id'=>'highSchool']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'hboBachelor', false, ['id'=>'hboBachelor']) }}
							{{ Form::label('hboBachelor', 'Pre-university/hbo-bachelor (Dutch)', ['id'=>'hboBachelor']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'uniBachelor', false, ['id'=>'uniBachelor']) }}
							{{ Form::label('uniBachelor', 'University bachelor', ['id'=>'uniBachelor']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'hboMaster', false, ['id'=>'hboMaster']) }}
							{{ Form::label('hboMaster', 'Pre-university/hbo master (Dutch)', ['id'=>'hboMaster']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'uniMaster', false, ['id'=>'uniMaster']) }}
							{{ Form::label('uniMaster', 'University Master', ['id'=>'uniMaster']) }} <BR>
							{{ Form::radio('cellBioExpertise', 'phd', false, ['id'=>'phd']) }}
							{{ Form::label('phd', 'Phd', ['id'=>'phd']) }}</td>
					</tr>
					<tr>
						<td>Field of expertise:</td>
						<td>{{ Form::text('expertise', Auth::user()->get()->expertise, ['placeholder' => 'Expertise']) }}</td>
					</tr>
					<tr>
						<td></td>
						<td>{{ Form::submit('Save changes', array('class' => 'editProfileButton')) }}</td>
					</tr>
				</table>
			{{ Form::close() }}
		</div>
		<div style="clear:both">
		</div>
	</div>
@endif
