<html>
<head>
<title>Config</title>
<meta name="viewport" content="initial-scale=2.0">
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<script type="text/javascript">
  function doStart() {
   $("#result").load('doRDS.php?action=on');

  }
  
  function doStop() {
   $("#result").load('doRDS.php?action=off');

  }
</script>
</head>
<body>
<form>
<table width="100%">
<tr>
<td>Start</td>
<td><input type="button" value="start" onclick="javascript:doStart()"></td>
</tr>
<tr>
<td>Stop</td>
<td><input type="button" value="stop" onclick="javascript:doStop()"></td>
</tr>
<tr>
<td valign="top">Result</td>
<td><div id="result"></div></td>
</tr>
<tr>
<td colspan="2"><input type="button" value="all done" onclick="javascript:window.location.href = 'index.php';"></td>
</table>
</form>
</body>
</html>