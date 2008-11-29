<html>
<head><title>Top Scraper Users</title></head>
<body>
<table>
	<tr><td><b>Visits</b></td><td colspan=2><b>User</b></td><td><b>Last Visit</b></td><td><b>First Visit</b></td></tr>

<?php

	$count = 0;
	
	$dateformat = "Y-m-d H:i:s";

	foreach($query->result_array() as $r)
	{
		echo "<tr><td>".$r['count(user)']."</td><td><a href=\"http://stackoverflow.com/users/".$r['user']."\">".$r['user']."</a></td>";
		echo "<td>(<a href=\"/tracker/chart/".$r['user']."\">chart</a>)</td><td>".date($dateformat, $r['max(date)'])."</td><td>".date($dateformat, $r['min(date)'])."</td></tr>\n";
		$count++;
	}

	echo "<small><i>count: $count</i></small>";

?>