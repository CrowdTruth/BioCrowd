<!doctype html>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ Lang::get('gamelabels.title') }}</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Begin headers -->
@include('headers')
<!-- End headers -->

<!-- Begin extraheaders -->
@yield('extraheaders')
<!-- End extraheaders -->
</head>

<body style="background:rgb(241, 235, 235);"><!--/background-image:url('http://www.medicine.usask.ca/acb/faculty/dr.-l.-dean-chapman/image010.jpg');<b style='font-family:Calibri;'><span style='font-size:20px;'><div style="height:8px;">&nbsp;</div>Total annotations: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12.000.342.3 <br />This weeks annotations: &nbsp;5.234</span></b>http://iamceege.github.io/tooltipster/#options/-->
		<span class='header contentbox' style="background:#66B921 ; width:100%; position:absolute; height:95px;"></span>
		<div class='container' style='padding-top:0px'>
			<div class='row main'>
				<!-- Begin header -->
				@include('header')
				<!-- End header -->				
				
				<div class='col-xs-12 headerspace' style="background:none; display:hidden; height:10px;"></div>
				<div id="blanket" style="display:none;"></div>

				@if (Session::has('flash_error'))
					<div class="alert">
						{{ Session::get('flash_error') }}
					</div>
				@endif

				<!-- Begin content -->
				@yield('content')
				<!-- End content -->

				@if (Auth::user()->check())
					<div class='col-xs-3 sidebar pull-right' style="height:660px; font-size:13px;">
						<!-- Begin sidebar -->
						@include('sidebar')
						<!-- End sidebar -->
					</div>
				@endif
			</div>
		</div>
    </body>
</html>
