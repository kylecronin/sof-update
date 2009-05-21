<html>
<head><title>Top Scraper Users</title></head>
<body>


<table>

<tr><td><b>count:</b></td><td><b><?=$num?></b></td><td> [
<a href="/tracker/stats/<?=$order?>/10/<?=$site?>/<?=$lt?>">10</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/30/<?=$site?>/<?=$lt?>">30</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/100/<?=$site?>/<?=$lt?>">100</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/200/<?=$site?>/<?=$lt?>">200</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/1000000/<?=$site?>/<?=$lt?>">all</a>&nbsp;
]</td></tr>

<tr><td><b>min:</b></td><td><b><?=$lt?></b></td><td> [
<a href="/tracker/stats/<?=$order?>/<?=$num?>/<?=$site?>/0">0</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/<?=$site?>/3">3</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/<?=$site?>/5">5</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/<?=$site?>/10">10</a>&nbsp;
]</td></tr>

<tr><td><b>site:</b></td><td><b><?=$site?></b></td><td> [
<a href="/tracker/stats/<?=$order?>/<?=$num?>/0/<?=$lt?>">both</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/1/<?=$lt?>">SO</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/2/<?=$lt?>">SF</a>&nbsp;
]</td></tr>

</table>

<table>
	<tr><td><b><a href="/tracker/stats/top/<?=$num?>/<?=$site?>/<?=$lt?>">Visits</a></b></td>
	    <td><b>Site</b></td>
	    <td colspan=2><b><a href="/tracker/stats/user/<?=$num?>/<?=$site?>/<?=$lt?>">User</a></b></td>
	    <td><b><a href="/tracker/stats/last/<?=$num?>/<?=$site?>/<?=$lt?>">Last Visit</a></b></td>
	    <td><b><a href="/tracker/stats/newbies/<?=$num?>/<?=$site?>/<?=$lt?>">First Visit</a></b></td>
	</tr>

<?php

    $count = 0;
    $dateformat = "m-d H:i";
    

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

    //echo "<b>count: $count</b> [10 30 100 200 500]";

?>
</table>
</body>
</html>
