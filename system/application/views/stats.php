<html>
<head><title>Top Scraper Users</title></head>
<body>
<table>
	<tr><td><b>Visits</b></td><td><b>Site</b></td><td colspan=2><b>User</b></td><td><b>Last Visit</b></td><td><b>First Visit</b></td></tr>

<?php

    $count = 0;
    $dateformat = "ymd H:i:s";
    

    foreach($query->result_array() as $r)
    {
        if ($r['site'] == 1)
        {
            $name = "stackoverflow.com";
            $abbrev = "SO";
        }
        else if ($r['site'] == 2)
        {
            $name = "serverfault.com";
            $abbrev = "SF";
        }
        else
        {
            $name = "UNKNOWN";
            $abbrev = "BAD!";
        }
    
    
    	echo "<tr><td>".$r['count(user)']."</td>";
    	echo "<td>$abbrev</td>";
    	echo "<td><a href=\"http://$name/users/".$r['user']."\">".$r['user']."</a></td>";
    	echo "<td>(<a href=\"/tracker/chart/".$r['user'].'/'.$r['site']."\">chart</a>)</td><td>".date($dateformat, $r['max(date)'])."</td><td>".date($dateformat, $r['min(date)'])."</td></tr>\n";
    	$count++;
	
    }

    echo "<small><i>count: $count</i></small>";

?>