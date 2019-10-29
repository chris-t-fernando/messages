<?

if ( isset($_GET["action"]) ) {
	
	if ( $_GET["action"] == "on" ) {
		exec("/usr/bin/python /projects/messages/startRDS.py 2>&1", $output);
		print_r($output);

		
	} elseif ( $_GET["action"] == "off" ) {
		exec("/usr/bin/python /projects/messages/stopRDS.py 2>&1", $output);
		print_r($output);
		
	} else {
		echo "something else";
		
	}
	
} else {
	echo "something1 else";

}


?>