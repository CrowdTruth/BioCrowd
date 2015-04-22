<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ URL::action('AdminController@home') }}">Admin</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">
				{{ HTML::headerMenu('Users' , [
					[	'url'	=>	URL::action('AdminController@newUserView'),
					 	'text'	=> 'Create user', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::USERS) ],
					[	'url'	=>	URL::action('AdminController@listUsersView'),
					 	'text'	=> 'List users',
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::USERS) ],
				] ) }}

				{{ HTML::headerMenu('Games' , [
					[	'url'	=>	URL::action('AdminController@listGamesView'),
					 	'text'	=> 'List all games', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::GAME) ],
					[	'url'	=>	URL::action('AdminController@listGameTypesView'),
					 	'text'	=> 'List all game types', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::GAMETYPE) ],
				] ) }}

				{{ HTML::headerMenu('Data' , [
					[	'url'	=>	URL::action('DataportController@exportToFileView'),
					 	'text'	=> 'Export data', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::EXPORTDATA) ],
					[	'url'	=>	URL::action('DataportController@webhookView'),
					 	'text'	=> 'Webhook', 
					 	'enabled'=> Auth::admin()->get()->hasPermission(AdminPermission::EXPORTDATA) ],
				] ) }}
			</ul>

			<ul class="nav navbar-nav navbar-right">
				{{ HTML::headerLink(URL::action('AdminController@requestLogout'), 'Logout') }}
			</ul>
		</div>
	</div>
</nav>
