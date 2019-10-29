<?

$warnings = false;

$inputHandle = fopen("hayley.csv", "r");
$inputArray = array();

while ( !feof($inputHandle) ) {
	$thisLine = fgetcsv($inputHandle);
	
	if ( $thisLine[0] == "Date" )
	{
		// do nothing - its the first line
		
	} elseif ( $thisLine[0] !== null ) 
	{
		array_push(&$inputArray, $thisLine);
		$lastLine = $thisLine;
		
	} else {
		if ( $warnings )
		{
			echo "Warning - dropped input line because it was blank/null.  Previous row was:\r\n";
			print_r($lastLine);
			
		}

	}

}

// clean up input - fix inner " characters and find any rows with less than 5 columns
for ( $i=0; $i < count($inputArray); $i++ )
{
	if ( count($inputArray[$i]) > 5 )
	{
		$reconstitutedString = $inputArray[$i][4];
		
		for ( $j=5; $j < count($inputArray[$i]); $j++ )
		{
			$reconstitutedString .= "\"" . $inputArray[$i][$j];
			
		}
		
		$inputArray[$i][4] = $reconstitutedString;

		// delete the original extra elements in the array now that its been reconstituted
		for ( $j=5; $j < count($inputArray[$i]); $j++ )
		{
			unset($inputArray[$i][$j]);
			
		}		
		
	} elseif ( count($inputArray[$i]) < 5 )
	{
		echo "Failed - the CSV row at " . $i . " has less than 5 columns\r\n";
		print_r($inputArray[$i-1]);
		die;
		
	}
	
}

$cleanedText = array();
date_default_timezone_set("Australia/Melbourne");

// have an array of CSV rows with 5 columns. need to format the input now - dates & RorS
for ( $i=0; $i < count($inputArray); $i++ )
{
	// fix & load dates
	$thisDate = explode(",", $inputArray[$i][0]);
	
	// if it exploded correctly there should be 3 elements - day of week, date, time
	if ( count($thisDate) === 3 )
	{
		$rawDate = strtotime($thisDate[1] . $thisDate[2]);
		$cleanedText[$i]["date"] = date('Y-m-d', $rawDate);
		$cleanedText[$i]["time"] = date('H:i:s', $rawDate);
				
	} else {
		echo "Failed - tried to split timestamp field by , but there were not 3 elements";
		print_r($thisDate);
		die;
		
	}
	
	// R or S
	if ( $inputArray[$i][2] == "Yes" )
	{
		$cleanedText[$i]["RorS"] = "R";
		
	} elseif ( $inputArray[$i][2] == "" )
	{
		$cleanedText[$i]["RorS"] = "S";
		
	}
	
	// message
	// encapsulate " in the middle of the string
	$cleanedText[$i]["message"] = str_replace("\"", "'", $inputArray[$i][4], $count);
	
}


// connect to mysql
$servername = "jtweets.ciizausrav91.us-west-2.rds.amazonaws.com";
$username = "jtweets";
$password = "jtweets1";

// Create connection
$con = new mysqli($servername, $username, $password, "messages");

// Check connection
if ($con->connect_error) {
    die("Failed - unable to connect to SQL.\r\nError message: " . $conn->connect_error . "\r\n");

}

// get the latest message inserted
if ( $result = $con->query('select * from messages where date=(select max(date) from messages order by date desc) order by time desc limit 1') )
{	
	$lastMessage = $result->fetch_array(MYSQLI_ASSOC);
	
} else {
	echo "Failed - tried to grab message with most recent timestamp but query failed\r\n";
	echo 'SQL: select * from messages where date=(select max(date) from messages order by date desc) order by time desc limit 1\r\n';
	printf("\r\nError message: %s\r\n", $con->error);
	die;
	
}

if ( $lastMessage == null )
{
	if ( $warnings ) { echo "Warning - DB is empty?  Defaulting to 1983\r\n"; }
	
	$lastMessage["dateTime"] = strtotime("24-01-1983 09:00");
	
} else {
	$lastMessage["dateTime"] = strtotime($lastMessage["date"] . " " . $lastMessage["time"]);
	
}

// make the array, filtering out any that already exist
$sql = array();
foreach ($cleanedText as $row)
{
	$thisDateTime = strtotime($row["date"] . " " . $row["time"]);
	$newRow = false;
	
	if ( $thisDateTime == $lastMessage["dateTime"] )
	{
		// need to look into this more - possibly in, maybe not
		if ( $result = $con->query('select * from messages where date="' . $row["date"] . '" and time="' . $row["time"] . '" and RorS="' . $row["RorS"] .'" and text="' . $row["message"] . '"') )
		{
			if ( $result->num_rows === 0 )
			{
				// new row
				$newRow = true;
				
			}
			
		} else {
			echo "Failed - tried to check if this row already existed in DB but query failed\r\n";
			echo 'SQL: select * from messages where date="' . $row["date"] . '" and time="' . $row["time"] . '" and RorS="' . $row["RorS"] .'" and text="' . $row["message"] . '"\r\n';
			echo "Row: " . print_r($row);
			printf("\r\nError message: %s\r\n", $con->error);
			die;
			
		}
		
	} elseif ( $thisDateTime > $lastMessage["dateTime"] ) {
		// this is newer than what's in the DB, so load it up
		$newRow = true;
		
	}
	
	// compose the array of rows to insert
	if ( $newRow )
	{
		$sql[] = '("' . $row["date"] . '","' . $row["time"] . '","' . $row["RorS"] . '","' . $row["message"] . '")';
		
	}
	
}

// if there's something to do
if ( count($sql) > 0 )
{	
	// if the insert works
	if ( $con->query('INSERT INTO messages (date, time, RorS, text) VALUES '.implode(',', $sql)) )
	{
		echo "Success - new records inserted\r\n";
		die;
		
	} else {
		echo "Failed - couldn't insert records`\r\n";
		printf("Error message: %s\n", $con->error);
		die;
		
	}
} else {
	echo "Success - nothing new to insert though";
	
}

?>