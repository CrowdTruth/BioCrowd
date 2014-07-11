<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-theme.css" rel="stylesheet">
<link href="css/gamestyle.css" rel="stylesheet">
<link href="jquery.tablesorter/themes/blue/style.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter/jquery.tablesorter.js"></script>
<script>
	$(function () {
		var tabs = $('div[id^=tab]');
		var imgs = $('div[id^=img]');
		tabs.hide();
		imgs.hide();
		$('#tab1').show();
		$('.tabs a').click(function () {
			var tab_id = $(this).attr('href'); 
			tabs.hide();
			$(tab_id).show(); 
		});
		
		$('.block').hide();
		$('#option1').show();
			$('#selectField1,#selectField2').change(function () {
				$('.block').hide();
				$('#'+$(this).val()).fadeIn();
			});
			
	
	
		
		<!--/highlight the clicked tab in the sidebar/-->
		$('#tabs li a').click(function() {
			$(this).closest("li").addClass('highlight').siblings().removeClass('highlight');
			return(false);   // no default behavior from clicking on the link
		});
		
	});
</script>
<script type='text/javascript'>
	<!--/show the right content based on the selected element in the dropdown list of the sidebar/-->
	$(document).ready(function(){
		$("#myTable").tablesorter(); 

		$('#view1').show();
		$('#view2a').hide();
		$('#view2b').hide();
		$('#view3').hide();
	$.viewMap = {
		'view1' : $('#view1'),
		'view2' : $('#view2a'),
		'view3' : $('#view3')
	  };

	  $('#viewSelector').change(function() {
		// hide all
		$.each($.viewMap, function() { this.hide(); });
		// show current
		$.viewMap[$(this).val()].show();
	  });
	})
	
	$(window).load(function(){
		$(document).ready(function () {
			$('.sidebar_dropdown_content_performance').hide();
			$('#performance1').show();
			$('#selectField_performance').change(function () {
				$('.sidebar_dropdown_content_performance').hide();
				$('#'+$(this).val()).fadeIn();
			});
			
			$('.sidebar_dropdown_content_collection').hide();
			$('#collection1').show();
			$('#selectField_collection').change(function () {
				$('.sidebar_dropdown_content_collection').hide();
				$('#'+$(this).val()).fadeIn();
			});	

			$('.sidebar_dropdown_content_challenges').hide();
			$('#challenges1').show();
			$('#selectField_challenges').change(function () {
				$('.sidebar_dropdown_content_challenges').hide();
				$('#'+$(this).val()).fadeIn();
			});	

			$('.showhide').click(function() {
				if($('.sidebar').hasClass('hidden'))
				{
					$('.maincolumn').removeClass('col-xs-12').addClass('col-xs-9');
					$('.hideButton').text('hide');
					$('.sidebar').removeClass('hidden');
				} else {
					$('.maincolumn').removeClass('col-xs-9').addClass('col-xs-12');
					$('.sidebar').addClass('hidden');
					$('.hideButton').text('show');
				}
			});
			
		}); 
	});	 
</script>
<script>
			$(document).ready( function() {
				$("#link,#closeLink").click( function () { popup('popUpDiv')});
			});

			function toggle(div_id) {
				var el = document.getElementById(div_id);
				if ( el.style.display == 'none' ) {	el.style.display = 'block';}
				else {el.style.display = 'none';}
			}
			function blanket_size(popUpDivVar) {
				if (typeof window.innerWidth != 'undefined') {
					viewportheight = window.innerHeight;
				} else {
					viewportheight = document.documentElement.clientHeight;
				}
				if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
					blanket_height = viewportheight;
				} else {
					if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
						blanket_height = document.body.parentNode.clientHeight;
					} else {
						blanket_height = document.body.parentNode.scrollHeight;
					}
				}
				var blanket = document.getElementById('blanket');
				blanket.style.height = blanket_height + 'px';
				var popUpDiv = document.getElementById(popUpDivVar);
			}
			function window_pos(popUpDivVar) {
				if (typeof window.innerWidth != 'undefined') {
					viewportwidth = window.innerHeight;
				} else {
					viewportwidth = document.documentElement.clientHeight;
				}
				if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
					window_width = viewportwidth;
				} else {
					if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
						window_width = document.body.parentNode.clientWidth;
					} else {
						window_width = document.body.parentNode.scrollWidth;
					}
				}
				var popUpDiv = document.getElementById(popUpDivVar);
				window_width=window_width/2-200;//200 is half popup's width
				popUpDiv.style.left = window_width + 'px';
			}
			function popup(windowname) {
				blanket_size(windowname);
				window_pos(windowname);
				toggle('blanket');
				toggle(windowname);		
			}
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Factorspan',     11],
          ['Passage alignment',      2],
          ['Relation direction',  2],
        ]);

        var options = {
          title: 'Tasks completed',
		  legend: 'none',
		  pieSliceText: 'none',
          pieHole: 0.6,
		  backgroundColor: 'none'
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
</script>
<script type="text/javascript">
      google.load('visualization', '1', {packages: ['table']});
</script>
<script type="text/javascript">
    function drawVisualization() {
      // Create and populate the data table.
      var data = google.visualization.arrayToDataTable([
        ['Place', 'Name', 'Wins'],
        [1, 'Lora', 15],
        [2, 'Khalid', 13],
        [6, 'You', 6]
      ]);
    
      // Create and draw the visualization.
      visualization = new google.visualization.Table(document.getElementById('table'));
      visualization.draw(data, null);
    }
    

    google.setOnLoadCallback(drawVisualization);
</script>
<script type="text/javascript">
    function drawVisualization() {
      // Create and populate the data table.
      var data2 = google.visualization.arrayToDataTable([
        ['Place', 'Name', 'Contributions'],
        [1, 'Rens', 23],
        [2, 'Khalid', 19],
        [6, 'You', 12]
      ]);
    
      // Create and draw the visualization.
      visualization = new google.visualization.Table(document.getElementById('table2'));
      visualization.draw(data2, null);
    }

    google.setOnLoadCallback(drawVisualization);
</script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["treemap"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Location', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
          ['Win/Loss ratio',    null,                 0,                               0],
          ['Wins: 12',   'Win/Loss ratio',             60,                               2],
          ['Losses: 8',    'Win/Loss ratio',             40,                               1],

        ]);
      }
</script>
<script>
		function fullVersion() {
			 alert("This will be available in the full version!"); 
		};
		
		function addedToCollection() {
			 alert("This article has been added to your collection!"); 
		};
</script>
<script>
	//new test
	document.getElementById('options').onchange = function() {
	    var i = 1;
	    var myDiv = document.getElementById(i);
	    while(myDiv) {
	        myDiv.style.display = 'none';
	        myDiv = document.getElementById(++i);
	    }
	    document.getElementById(this.value).style.display = 'block';
	};
</script>
