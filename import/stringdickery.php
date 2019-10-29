<?

$trans = array(
	"‚Äô" => "'",
	"‚Äò" => "'"
	
);

//$badTick = "‚Äô";
//$badTick2 - "‚Äò";

$inputHandle = fopen("hayley.csv", "r");
$inputText = null;

while(!feof($inputHandle)) {		
	//$inputText .= str_replace($badTick, "'", iconv('macintosh', 'UTF-8//TRANSLIT', fgets($inputHandle)));	
	
	$inputText .= strtr(iconv('macintosh', 'UTF-8//TRANSLIT', fgets($inputHandle)), $trans);
	//echo $inputText;
}

fclose($inputHandle);


for ( $i=0; $i<strlen($inputText); $i++ )
{
	if ( ord($inputText[$i]) > 127 )
	{
		echo substr($inputText, $i-20, 60) . "\r\n";
		
		
		//echo "got one: " . $inputText[$i] . "\r\n";
		
	}
	
	
}


die;

// fucked shit

$fHandle = fopen("hayley.csv", "r");
//$str = fread($fHandle, filesize("hayley.csv"));

$fWriteHandle = fopen("out.txt", "w");
/*
for ( $i=0; $i<strlen($str); $i++ )
{
	
	if ( ord($str[$i]>127) )
	{
		echo "Got one: " . ord($str[$i]) . " " . $str[$i] . "\r\n";
		die;
		
	}
	
}
*/

//iconv('ASCII', 'UTF-8//IGNORE', $string);
	//var_dump(iconv_get_encoding(fgets($fHandle)));
	
while(!feof($fHandle)) {
	//print_r(iconv_get_encoding(fgets($fHandle)));
	//die;
	
	fwrite($fWriteHandle, iconv('macintosh', 'UTF-8//TRANSLIT', fgets($fHandle)));
}
fclose($fWriteHandle);
fclose($fHandle);


/*
// orig
$string = "Friday, Dec 01 2017, 19:26\",Hayley Over(+61417017950),,Yes,Poor baby. She‚Äôs";

echo ord(substr($string, -1));
echo "\r\n" . chr(39);
echo "\r\n" . strlen($string);
die;
*/

/*
echo "len is: " . strlen("Date,Sender,Received,iMessage,Text
\"Friday, Dec 01 2017, 06:19\",Hayley Over(+61422032474),Yes,,Hang Up Message: 0422032474 reached your MessageBank on 01/12/2017 at 06:19 & did not leave a message.
\"Friday, Dec 01 2017, 19:22\",Hayley Over(+61422032474),Yes,Yes,How can I contact you?
\"Friday, Dec 01 2017, 19:22\",Hayley Over(+61422032474),Yes,Yes,Do you want to know about smooshie being unwell?
\"Friday, Dec 01 2017, 19:23\",Hayley Over(+61417017950),,Yes,Yes I do
\"Friday, Dec 01 2017, 19:23\",Hayley Over(+61417017950),,Yes,Just got home from gym. Was just thinking of how she went
\"Friday, Dec 01 2017, 19:24\",Hayley Over(+61422032474),Yes,Yes,\"Sorry, I can't talk right now.\"
\"Friday, Dec 01 2017, 19:24\",Hayley Over(+61422032474),Yes,Yes,I messaged you on Facebook
\"Friday, Dec 01 2017, 19:25\",Hayley Over(+61422032474),Yes,Yes,Is it not working?
\"Friday, Dec 01 2017, 19:25\",Hayley Over(+61422032474),Yes,Yes,Temp 40 today
\"Friday, Dec 01 2017, 19:26\",Hayley Over(+61422032474),Yes,Yes,She needs to go to the specialist
\"Friday, Dec 01 2017, 19:26\",Hayley Over(+61422032474),Yes,Yes,This is crazy!
\"Friday, Dec 01 2017, 19:26\",Hayley Over(+61417017950),,Yes,Did you book her in? With the specialist I got the referral for?
\"Friday, Dec 01 2017, 19:26\",Hayley Over(+61417017950),,Yes,Poor baby. She");
die;
*/

$str=file_get_contents('out.txt');

//echo $str[1318] . $str[1319] . $str[1320] . $str[1321] . $str[1322] . $str[1323] . $str[1324] . $str[1325] . "\r\n";
//echo ord($str[1318]) . " " . ord($str[1319]) . " " . ord($str[1320]) . " " . ord($str[1321]) . " " . ord($str[1322]) . " " . ord($str[1323]) . " " . ord($str[1324]) . " " . ord($str[1325]);

//$badSingleMark = chr(226) . chr(128) . chr(154) . chr(195) . chr(180) . chr(115);

$badSingleMark = substr($str, 1318, 7);

//echo "\r\n" . $badSingleMark . "\r\n";
//die;

//echo $badSingleMark;
//die;

$str = str_replace($badSingleMark, "'", $str, $count);
echo "found: " . $count;

for ( $i=0; $i<strlen($str); $i++ )
{
	//echo ord($str[$i]) . "\r\n";
	if ( ord($str[$i]>127) )
	{
		echo "Got one: " . ord($str[$i]) . " " . $str[$i] . "\r\n";
		
		
	}
	
	
}



die;


copy ( "original.csv" , "working.csv" );

$str=file_get_contents('working.csv');


// replace weird ' marks
$str=str_replace("â€™", "'", $str, $count);
echo "First changes: " . $count;

$str=str_replace("â€˜", "'", $str);
echo "Second changes: " . $count;



//write the entire string
file_put_contents('msghistory.txt', $str);

?>