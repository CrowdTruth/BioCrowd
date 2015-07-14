@extends('layout') 

@section('extraheaders')
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
@stop

@section('content')
<div id="mainsection" class="section group">
	<div id="main" class="col">
		<div class="section group" id="profile">
			<div class="col span_5_of_8" id="profile_main">
				<form>
					<div class="textblock">
						<div class="section group" id="account">
							<div class="col span_8_of_8">
								<div class="profiletitle">
									<H1>My Account <img src="img/glyphs/arrow_g-01.png" height="15px" width="25px"></H1>

								</div>
								<div class="profilebody">
									<table>
										<tr>
											<td>Username</td>
											<td><input type="text" value="Merelrocks"
												placeholder=""></td>
										</tr>
										<tr>
											<td>E-mail</td>
											<td><input type="text" value="Merel123@gmail.com"></td>
										</tr>
										<tr>
											<td>Facebook connect</td>
											<td><input type="button" value="Connect"></td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<div class="section group" id="password">
							<div class="col span_8_of_8">
								<div class="profiletitle">
									<H1>Password <img src="img/glyphs/arrow_g-01.png" height="15px" width="25px"></H1>
								</div>
								<div class="profilebody">
									<table>
										<tr>
											<td>Current password</td>
											<td><input type="password"></td>
										</tr>
										<tr>
											<td>New Password</td>
											<td><input type="password"></td>
										</tr>
										<tr>
											<td>Confirm new password</td>
											<td><input type="password"></td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<div class="section group" id="notifications">
							<div class="col span_8_of_8">
								<div class="profiletitle">
									<H1>Notifications <img src="img/glyphs/arrow_g-01.png" height="15px" width="25px"></H1>
								</div>
								<div class="profilebody">
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
				</form>
			</div>
			<div class="col span_3_of_8" id="profile_background">
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