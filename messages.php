<html>
<head>
	<title>Message viewer</title>
	<meta name="viewport" content="initial-scale=1.0">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<style>
		div.ui-datepicker{
			font-size:10px;
		}
		
	</style>
	<script type="text/javascript" src="jquery-3.3.1.js"></script>
	<script>
		$( function() {
			$( "#from" ).datepicker();
			$( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		} );
		  
		$( function() {
			$( "#to" ).datepicker();
			$( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		} );

		function doSearch() {
			// validate date
			if ( validateDate($('#from').val(), "from") && validateDate($('#to').val(), "to") ) {
				
				// pass from and to in GET, validate it in php
				
				$("#results").load('search.php?query='+encodeURI($('#term').val())+'&from='+encodeURI($('#from').val())+'&to='+encodeURI($('#to').val()));
				
			}
			
		}
		
		function validateDate(theDate, whichDate) {
			if ( theDate.length !== 10 && theDate.length !== 0 ) { alert("Wrong length in " + whichDate); return false; }
			
			// TO DO: put in more validation - dashes.  Don't need to worry about characters
			
			return true;
			
		}
		
	</script>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body style="margin: 0;padding: 0; overflow-y: hidden;">
<form name="searchInputs">
	<table border="1" width-"100%">
		<tr>
			<td>
				<div id="search" style="-webkit-overflow-scrolling: touch; overflow: auto; overflow-y: initial; height:55px; width:370px">
					<table border="0">
<tr><td>Term</td><td valign="center"><input type="text" size="45" id="term" /><a href="config.php"><img src="settings.png" height="17" width="17"></a></td></tr>
<tr><td>From</td><td><input type="text" size="10" id="from" /> to <input type="text" size="10" id="to" />
<input type="button" value="search" onclick="javascript:doSearch()"></td></tr>
</table>

				</div>
			</td>
		</tr>

                <tr>
                        <td>
                                <div id="results" style="-webkit-overflow-scrolling: touch; overflow: auto; overflow-y: initial; height:160px; position:relative;">
search results & allow me to push to slot

                                </div>
                        </td>
                </tr>


		<tr>
			<td>
				<div id="controls" style="-webkit-overflow-scrolling: touch; overflow: auto; overflow-y: hidden; height:160px;">
Show search parameters and index.  Option to clear.  Option to swap with another frame?  Option to previous/next

				</div>
			</td>
		</tr>
                <tr>
                        <td>
                                <div id="controls" style="-webkit-overflow-scrolling: touch; overflow: auto; overflow-y: hidden; height:160px;">
                                </div>
                        </td>
                </tr>
                <tr>
                        <td>
                                <div id="controls" style="-webkit-overflow-scrolling: touch; overflow: auto; overflow-y: hidden; height:160px;">
                                </div>
                        </td>
                </tr>


	</table>
</form>

<div style="position: absolute; height: 163px; width: 30px; top:65px; left: 350px; background-color: orange">
Hi
</div>

<div style="position: absolute; height: 163px; width: 30px; top:231px; left: 350px; background-color: orange">
Hi
</div>

<div style="position: absolute; height: 163px; width: 30px; top:397px; left: 350px; background-color: orange">
Hi
</div>

<div style="position: absolute; height: 163px; width: 30px; top:563px; left: 350px; background-color: orange">
Hi
</div>


</body>
</html>
