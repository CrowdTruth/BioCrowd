{{-- 
Originally, scripts were on css/, js/ and some cross-site scripting (ajax.googleapis.com)
But they used different versions, which we want to unify.

So, we put all the scripts under css_final/ and js_final/
Once we are ready for release of a 'stable' version, we will move
css_final/ and js_final to css/ and js/ and change the links here.
--}}

{{-- THESE ARE THE OLD LINKS
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-theme.css" rel="stylesheet">
<link href="css/gamestyle.css" rel="stylesheet">
<link href="jquery.tablesorter/themes/blue/style.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter/jquery.tablesorter.js"></script>
--}}

{{-- THESE ARE THE NEW LINKS --}}
<link href="css_final/bootstrap.css" rel="stylesheet">
<link href="css_final/bootstrap-theme.css" rel="stylesheet">
<link href="css_final/gamestyle.css" rel="stylesheet">
<link href="css_final/CSS.css" rel="stylesheet">
<script src="js_final/jquery.min.js"></script>
<script src="js_final/bootstrap.min.js"></script>
<script src="js_final/JavaScript.js"></script>

