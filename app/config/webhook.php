<?php

return array(
    'URL' => 'http://dev.crowdtruth.org/game/detective/',
	//This is the maximal amount of judgements sent to Crowdtruth via the API per chunk. 
	//Set this higher for better performance, but the trade off is bigger post data (which could result in vague errors) 
	'chunksize' => '1', 
);
