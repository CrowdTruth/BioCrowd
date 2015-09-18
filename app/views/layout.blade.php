<!DOCTYPE html>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Pacifico'
		rel='stylesheet' type='text/css'>
	<meta charset="ISO-8859-1">
	<title>..:: {{ Lang::get('gamelabels.gamename') }} ::..</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Begin headers -->
	@include('headers')
	<!-- End headers -->
	<!-- Begin extraheaders -->
	@yield('extraheaders')
	<!-- End extraheaders -->
</head>
	</head>
	<body>
		<div class="wrap">
			<!-- Begin header -->
			@include('header')
			<!-- End header -->				

			@if (Session::has('flash_error'))
				<div class="alert">
					{{ Session::get('flash_error') }}
				</div>
			@endif

			<!-- Begin content -->
			@yield('content')
			<!-- End content -->
			<div class="footer">
				<a href="http://crowdtruth.org/"><img src="img/logo/CrowdTruth_gs-01.png" height="30px"></a>
				<a href="https://www-927.ibm.com/ibm/cas/"><img src="img/logo/logo_ibm_g-01.png" height="30px"></a>			
			</div>
		</div>
		<div class="G-analytics">
			{{ Lang::get('gamelabels.googleAnalytics') }}
		</div>
    </body>
</html>
