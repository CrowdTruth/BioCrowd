<?php

//@extends('/app/config/database.php') Can we include this file for the database info?

/**
 * Submit the annotationdata made by the user to the database. 
 */

function submitAnnotation() {
		// Make a connection to the database
		connectToDB();
	}
	
function connectToDB(){
	$dbconn=mysql_connect('localhost', 'crowdtruth', 'crowdtruth');
	
	//print_r($connections); use this to print the connections variable from /app/config/database.php
	
	if (!$dbconn) {
		die('Could not connect: ' . mysql_error());
	}
	else{
		//mysql_select_db(dbname(), $dbconn);
		//return $dbconn;
		echo "we did it!"
	}
}
	