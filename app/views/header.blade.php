<div class='header contentbox' style="background: none; border: none;; width: 100%; margin-left: 10px;">
	<div class='col-xs-2' style='background: none'>
		<a href="/"><img class='iconImg' src='img/logo.jpg'></img></a>
	</div>
	<div class='col-xs-4' style='background: none;'>
		<b style='font-family: Calibri;'><div style="height: 5px;">&nbsp;</div>
			<p>
				<span style='font-size: 24px; margin-left: -56px;'>{{ Lang::get('gamelabels.header.title') }}</span><br />
				<span style='font-size: 20px; margin-left: -56px;'>{{ Lang::get('gamelabels.header.subtitle') }}</span>
			</p>
		</b>
	</div>
	<div class='col-xs-4' style='background: none;'>
		<b style='font-family: Calibri;'><span style='font-size: 20px;'>
			<div style="height: 8px;">&nbsp;</div>{{ Lang::get('gamelabels.header.right') }}</span>
		</b>
	</div>
	@if (Auth::user()->check())
		<div class='col-xs-2' style='background: none;'>
			<a href="logout"><h5 style="text-align:right; margin-top:2px; margin-bottom:-13px; color:black; font-size:15px;"><button class="logoutButton">logout</button></h5></a>
		</div>
	@endif
</div>
