<?
// connect to mysql
$servername = "jtweets.ciizausrav91.us-west-2.rds.amazonaws.com";
$username = "jtweets";
$password = "jtweets1";

// Create connection
$con = new mysqli($servername, $username, $password, "messages");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// build query
$from = false;
$to = false;

if ( validateDate($_GET["from"]) ) {
	$from = true;

}

if ( validateDate($_GET["to"]) ) {
	$to = true;
	
}

//$query .= ";";

$searchText = "%" . $_GET["query"] . "%";

// from and to are valid and set
if ( $from && $to ) {
	$stmt = $con->prepare('SELECT * FROM messages where text like ? and date >= ? and date <= ?');
	$stmt->bind_param('sss', $searchText, $_GET["from"], $_GET["to"]); // 's' specifies the variable type => 'string'

// only from is set and valid
} elseif ( $from ) {
	$stmt = $con->prepare('SELECT * FROM messages where text like ? and date > ? ');
	$stmt->bind_param('ss', $searchText, $_GET["from"]); // 's' specifies the variable type => 'string'

// only to is set and valid
} elseif ( $to ) {
	$stmt = $con->prepare('SELECT * FROM messages where text like ? and date < ?');
	$stmt->bind_param('ss', $searchText, $_GET["to"]); // 's' specifies the variable type => 'string'

// neither from nor to are set or valid
} else {
	$stmt = $con->prepare('SELECT * FROM messages where text like ?');
	$stmt->bind_param('s', $searchText); // 's' specifies the variable type => 'string'

}

$stmt->execute();
echo "<table border=1>";

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
	
	// highlight text and add BRs
	$processedText = str_replace($_GET["query"], "<span style='background-color: #FFFF00'>" . $_GET["query"] . "</span>", $row["text"]);
	$processedText = str_replace("\n", "<br>", $processedText);
	
	// create date format
	$date = date_create($row["date"] . " " . $row["time"]);
	$thisDate = date_format($date,"d/m/Y h:ia");
	
	echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $thisDate . "</td>";
	echo "<td>" . $row["RorS"] . "</td>";
	echo "<td>" . $processedText . "</td>";
    echo "</tr>";

}
echo "</table>";

mysqli_close($con);


function validateDate($date) {
	$test_arr  = explode('-', $date);
	
	if (count($test_arr) == 3) {
		if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
			// valid date ...
			return true;
			
		} else {
			// problem with dates ...
			return false;
			
		}
	} else {
		// problem with input ...
		return false;
		
	}
	
}
?>
