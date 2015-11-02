@extends('layout') 

@section('extraheaders')
	<link href="css/profile.css" rel="stylesheet">
	<script>
		$(document).ready(function() {
			$('.profiletitle').on({
			    'click': function(){
			    	if ($(this).hasClass("collapsed")) {
					$(this).removeClass("collapsed")
			    	$(this).next().slideDown()	
					$(this).children().children("img").attr({src: "img/glyphs/arrow_g-01.png", height: "15px", width: "25px"})
			    	} else {
			    		$(this).addClass("collapsed")
						$(this).next().slideUp()
						$(this).children().children("img").attr({src: "img/glyphs/arrow_g-02.png", height: "25px", width: "15px" })
						
			    	}
			    }
		
			});
		
		});
	
	
	</script>
	<script type="text/javascript">
		//On window size < 788, remove image
		$(window).resize(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 788) {
				$("#profile_background").hide()
				$("#profile_main").removeClass("span_5_of_8")
				$("#profile_main").addClass("span_8_of_8")		
			} else if (width > 788) {
				$("#profile_background").show()
				$("#profile_main").removeClass("span_8_of_8")
				$("#profile_main").addClass("span_5_of_8")
			}
		});
	
		//Reset logo div in front of button on larger size.
		$(document).ready(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (width <= 788) {
				$("#profile_background").hide()
			} else if (width > 788) {
				$("#profile_background").show()
			}
		});
	</script>
	
	<script>
	$(document).ready(function() {
		var checkedBioExpertise = "<?php Print(Auth::user()->get()->cellBioExpertise); ?>";
		$('#'+checkedBioExpertise ).attr('checked', true);
	});
	</script>
@stop

@section('content')
<div id="mainsection" class="section group">
	<div id="main" class="col">
		<div class="section group" id="profile">
			<div class="col span_5_of_8" id="profile_main">
				<div class="textblock">
				
					<div class="section group" id="badges">
						<div class="col span_8_of_8">
							<div class="profiletitle collapsed">
								<H1>My Badges <img src="img/glyphs/arrow_g-02.png" height="25px" width="15px"></H1>
							</div>
							<div class="profilebody" style="display: none;">
								@if($userHasBadges)
									<div> 
									@foreach ($userHasBadges as $badge)
										<img width="20%" src="{{ $badge['image'] }}" title="{{ $badge['name'] }}">
									@endforeach
									</div>
								@else
									<div>You don't have any badges yet. Finish campaigns to earn badges. </div>
								@endif
							</div>
						</div>
					</div>
					
					<div class="section group" id="scores">
						<div class="col span_8_of_8">
							<div class="profiletitle collapsed">
								<H1>My Scores <img src="img/glyphs/arrow_g-02.png" height="25px" width="15px"></H1>
							</div>
							<div class="profilebody" style="display: none;">
								@if($userCampaignScores)
									Last 5 scores for Campaigns: 
									<div> 
									<table class="table table-striped">
										<tr>
											<td>Score gained</td>
											<td>Date</td>
										</tr>
									@foreach ($userCampaignScores as $campaignScore)
										<tr>
										<td>{{ $campaignScore['score_gained'] }}</td>
										<td>{{ $campaignScore['created_at'] }}</td>
										</tr>
									@endforeach
									</table>
									</div>
								@else
									<div>You don't have any Campaign scores yet. Finish campaigns to earn scores. </div>
								@endif
								<br>
								@if($userScores)
									Last 5 scores in general: 
									<div> 
									<table class="table table-striped">
										<tr>
											<td>Score gained</td>
											<td>Date</td>
										</tr>
									@foreach ($userScores as $userScore)
										<tr>
										<td>{{ $userScore['score_gained'] }}</td>
										<td>{{ $userScore['created_at'] }}</td>
										</tr>
									@endforeach
									</table>
									</div>
								@else
									<div>You don't have any scores yet. Finish campaigns and play games to earn scores. </div>
								@endif
							</div>
						</div>
					</div>
				
					<div class="section group" id="account">
						<div class="col span_8_of_8">
							<div class="profiletitle collapsed">
								<H1>My Account <img src="img/glyphs/arrow_g-02.png" height="25px" width="15px"></H1>

							</div>
							<div class="profilebody"  style="display: none;">
								{{ Form::open(['url' => 'editProfile']) }}
								<?php $cellBioExpertise = Auth::user()->get()->cellBioExpertise; ?>
								<table>
									<tr>
										<td>E-mail</td>
										<td>{{ Form::text('email', Auth::user()->get()->email, ['required' => 'required']) }}</td>
									</tr>
									<tr>
										<td>Username</td>
										<td>{{ Form::text('name', Auth::user()->get()->name, ['required' => 'required']) }}</td>
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
								{{Form::close()}}
							</div>
						</div>
					</div>

					<div class="section group" id="password">
						<div class="col span_8_of_8">
							<div class="profiletitle collapsed">
								<H1>Password <img src="img/glyphs/arrow_g-02.png" height="25px" width="15px"></H1>
							</div>
							<div class="profilebody"  style="display: none;">
								{{ Form::open(['url' => 'changePass']) }}
								{{ Form::hidden('email', Auth::user()->get()->email) }}
								<table>
									<tr>
										<td>Current password</td>
										<td>{{ Form::password('password', array('placeholder' => 'Password')) }}</td>
									</tr>
									<tr>
										<td>New Password</td>
										<td>{{ Form::password('newpassword', array('placeholder' => 'New password')) }}</td>
									</tr>
									<tr>
										<td>Confirm new password</td>
										<td>{{ Form::password('newpassword2', array('placeholder' => 'Re-type password')) }}</td>
									</tr>
									<tr>
										<td></td>
										<td>{{ Form::submit('Change Password', array('class' => 'changePassButton')) }}</td>
									</tr>
								</table>
								{{Form::close()}}
							</div>
						</div>
					</div>

					<div class="section group" id="notifications">
						<div class="col span_8_of_8">
							<div class="profiletitle collapsed">
								<H1>Notifications <img src="img/glyphs/arrow_g-02.png" height="25px" width="15px"></H1>
							</div>
							<div class="profilebody" style="display: none;">
								<table>
									<tr>
										<td>Notify me of new campaigns</td>
										<td>
											<select name="campaigns">
												<option value="immediately">Immediately</option>
												<option value="daily">Daily</option>
												<option value="weekly">Weekly</option>
												<option value="monthly">Monthly</option>														
												<option value="never">Never</option>
											</select>
										</td>
														
									</tr>
									<tr>
										<td>Notify me of friend's activities</td>
										<td>													
											<select name="friends">
												<option value="immediately">Immediately</option>
												<option value="daily">Daily</option>
												<option value="weekly">Weekly</option>
												<option value="monthly">Monthly</option>														
												<option value="never">Never</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Notify me of news</td>
										<td>	
											<select name="news">
												<option value="immediately">Immediately</option>
												<option value="daily">Daily</option>
												<option value="weekly">Weekly</option>
												<option value="monthly">Monthly</option>														
												<option value="never">Never</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="col span_3_of_8" id="profile_background">
			<a href="logout"><button style="float:right" class="bioCrowdButton">Log out</button></a>
			<img src="img/backgrounds/image_profilebackground-02.png" width="300px">
			</div>
		</div>
	</div>
	@if (Auth::user()->check())
			<!-- Begin sidebar -->
			@include('sidebar')
			<!-- End sidebar -->
			@endif
</div>
@stop