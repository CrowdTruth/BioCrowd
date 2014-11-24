<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="admin">Admin</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				{{ HTML::headerMenuOpen([	URL::action('AdminController@newUserView'),
											URL::action('AdminController@listUsersView')
										], 'Users') }}
					{{ HTML::headerLink(URL::action('AdminController@newUserView'), 'Create user') }}
					{{ HTML::headerLink(URL::action('AdminController@listUsersView'), 'List users') }}
				{{ HTML::headerMenuClose() }}
				{{ HTML::headerMenuOpen([	URL::action('AdminController@newTaskView'),
											URL::action('AdminController@listTaskView')
										], 'Tasks') }}
					{{ HTML::headerLink(URL::action('AdminController@newTaskView'), 'Create task') }}
					{{ HTML::headerLink(URL::action('AdminController@listTaskView'), 'List all tasks') }}
				{{ HTML::headerMenuClose() }}
			</ul>
			<ul class="nav navbar-nav navbar-right">
				{{ HTML::headerLink(URL::action('AdminController@requestLogout'), 'Logout') }}
			</ul>
		</div>
	</div>
</nav>
