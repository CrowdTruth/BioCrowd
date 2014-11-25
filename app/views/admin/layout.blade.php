<!doctype html>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>ADMIN -- {{ Lang::get('gamelabels.title') }}</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Begin headers -->
@include('headers')
<!-- End headers -->

<!-- Begin extraheaders -->
@yield('extraheaders')
<!-- End extraheaders -->
</head>

<body style="background: rgb(241, 235, 235);">
	<div class="container">
		@if (Auth::admin()->check())
			<!-- Begin header -->
			@include('admin.header')
			<!-- End header -->
		@endif

		@if (Session::has('flash_error'))
			<!-- Begin error -->
			<div class="alert alert-danger">
				{{ Session::get('flash_error') }}
			</div>
			<!-- End error -->
		@endif
		
		@if (Session::has('flash_message'))
			<!-- Begin message -->
			<div class="alert alert-info">
				{{ Session::get('flash_message') }}
			</div>
			<!-- End message -->
		@endif

		<div class='col-xs-12'>
			<!-- Begin content -->
			@yield('content')
			<!-- End content -->
		</div>
	</div>
</body>
</html>
