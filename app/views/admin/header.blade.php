<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ URL::action('AdminController@getIndex') }}">Admin</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">
				{{ HTML::headerMenu('Games' , [
					[	'url'	=>	URL::action('GameAdminController@getListGames'),
					 	'text'	=> 'List all games', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::GAME) ],
					[	'url'	=>	URL::action('GameAdminController@getListGameTypes'),
					 	'text'	=> 'List all game types', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::GAMETYPE) ],
				] ) }}

				{{ HTML::headerMenu('Data' , [
					[	'url'	=>	URL::action('DataportController@getToFile'),
					 	'text'	=> 'Export data', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::EXPORTDATA) ],
					[	'url'	=>	URL::action('DataportController@getWebhook'),
					 	'text'	=> 'Webhook', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::EXPORTDATA) ],
				] ) }}
			</ul>

			<ul class="nav navbar-nav navbar-right">
				{{ HTML::headerLink(URL::action('AdminController@getLogout'), 'Logout') }}
			</ul>
		</div>
	</div>
</nav>
