<link href="css/bootstrap.css" rel="stylesheet">
<!--link href="css/bootstrap-theme.css" rel="stylesheet">
<link href="css/gamestyle.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script-->

<link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
<link href="js/popbox/popbox.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">
<script src="js/jquery/jquery-ui.min.js"></script>
<script src="js/jquery/jquery-1.11.2.min.js"></script>
<script src="js/jquery/jquery-1.7.1.min.js"></script>
<script src="js/popbox/popbox.js"></script>
<script src="js/popbox/popbox.min.js"></script>

@if (Auth::user()->check())
	<script type="text/javascript">
		@if ((Route::getCurrentRoute()->getPath() != 'playGame') && (Route::getCurrentRoute()->getPath() != 'playCampaign'))
		$(document).ready(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (!document.getElementById('sidebar')) {
				$('.sidebarbutton').hide();
			} else {
				if (width <= 610 && $('#sidebar').is(":visible")) {
					$('#sidebar').hide();
					$("#main").removeClass("span_6_of_8");
					$('.sidebarbutton').show();
					$('#minimize').show();
				} else if (width > 610) {
					$('#sidebar').show();
					$("#main").addClass("span_6_of_8");
					$('.sidebarbutton').hide();
					$('#minimize').hide();
				}
			}
	
		});
	
		$(window).resize(function() {
			var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			if (!document.getElementById('sidebar')) {
				$('.sidebarbutton').hide();
			} else {
				if (width <= 610) {
					$('#sidebar').hide();
					$("#main").removeClass("span_6_of_8");
					$('.sidebarbutton').show();
					$('#minimize').show();
					$('#sidebar').removeClass('visible');
				} else if (width <= 610 && $('#sidebar').length > 0) {
					$('.sidebarbutton').hide();
				} else if (width > 610) {
					$('#sidebar').show();
					$("#main").addClass("span_6_of_8");
					$('.sidebarbutton').hide();
					$('#sidebar').removeAttr('style');
					$('#sidebar').removeClass('visible');
					$('#minimize').hide();
				}
			}
		});
		@else
			$(document).ready(function() {
				debugger;
				var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
				if (!document.getElementById('sidebar')) {
					$('.sidebarbutton').show();
				} else {
					if ($('#sidebar').is(":visible")) {
						$('#sidebar').hide();
						$('.sidebarbutton').show();
						$('#minimize').show();
					}
				}

			});
		@endif
	
		$(document).ready(function() {
			$('.sidebarbutton, #minimize img').click(function() {
				var hidden = $('#sidebar');
				if (hidden.hasClass('visible')) {
					hidden.animate({
						"right" : "-300px"
					}, "slow", function() {
						$('#sidebar').hide();
						$('#sidebar').removeClass('visible');
					});
	
				} else {
					hidden.animate({
						"right" : "-300"
					}, "slow", function() {
						$('#sidebar').css({
							"float" : "right",
							"z-index" : "10",
							"position" : "absolute",
							"width" : "230px",
	
						});
						$('#sidebar').show();
					});
					hidden.animate({
						"right" : "0"
					}, "slow", function() {
	
						//	$('#sidebar').show
						$('#sidebar').addClass('visible');
	
					});
					hidden.animate({
						"right" : "0px"
					}, "slow").addClass('visible');
				}
			});
		});

		$(document).ready(function() {
			$('#campaignsIconInBanner').click(function() {
				var hidden = $('#campaignDropDowns');
				if (hidden.hasClass('visible')) {
					hidden.removeClass('visible');
					hidden.slideUp(50);
				} else {
					hidden.addClass('visible');
					hidden.slideDown(50);
				}
			});

			$('#gamesIconInBanner').click(function() {
				var hidden = $('#gameDropDowns');
				if (hidden.hasClass('visible')) {
					hidden.removeClass('visible');
					hidden.slideUp(50);
				} else {
					hidden.addClass('visible');
					hidden.slideDown(50);
				}
			});
			
			$(document).click(function (event) {
				if($(event.target).attr('id') != $('#campaignCount').attr('id')){
					var hidden = $('#campaignDropDowns');		
					if (hidden.hasClass('visible')) {
						hidden.removeClass('visible');
						hidden.slideUp(50);
					}
				}
				if ($(event.target).attr('id') != $('#gameCount').attr('id')){
					var hidden = $('#gameDropDowns');		
					if (hidden.hasClass('visible')) {
						hidden.removeClass('visible');
						hidden.slideUp(50);
					}
				}
			});
		});
	</script>
@endif

@if (!Auth::user()->check())
	<script>
		//popups for the login and signup divs.
		$(document).ready(function() {
			$(".button.login").click(function(e) {
				$(".popup").hide();
				$("body").append('');
				$(".popup.login").show();
				$(".close").click(function(e) {
					$(".popup, .overlay").hide();
	
				});
			});
			$(".button.signup").click(function(e) {
				$(".popup").hide();
				$("body").append('');
				$(".popup.signup").show();
				$(".close").click(function(e) {
					$(".popup, .overlay").hide();
				});
			});
		});
	</script>
@endif
{{ Lang::get('gamelabels.googleAnalytics') }}
	