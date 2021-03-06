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
			<div style="text-align:center;" class="footer">
				<a style="float: left;" href="http://crowdtruth.org/"><img id="crowdTruthFooterLogo" src="img/logo/CrowdTruth_gs-01.png" height="30px"></a>
				<a href="http://www.vu.nl/en/index.asp"><img id="VUFooterLogo" src="img/logo/VUlogo_NL_vrijstaand_druk.jpg" height="30px"></a>
				<a style="float: right; margin-top: 7px; margin-right:7px;" href="https://www-927.ibm.com/ibm/cas/"><img id="IBMFooterLogo" src="img/logo/logo_ibm_g-01.png" height="17px"></a>
			</div>
		</div>
    </body>
</html>
