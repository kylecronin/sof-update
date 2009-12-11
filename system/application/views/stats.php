<html>
<head><title>Top Scraper Users</title></head>
<style>
body {
		font-family:		Lucida Grande, Verdana, Sans-serif;
		font-size:			8pt;
		background-color: /*#4a5062;*/ #3e4453;
		color: white;
		}

body,td,th {
	font-size: 10pt;
}
a.new:link { color: #FFFFFF; }
a.new:visited { color: #FFFFFF; }
a.new:hover { color: #AAAAAA; }
a.new:active { color: #CCCCCC; }
a:link { color: #FFFFFF; }
a:visited { color: #DDDD77; }
a:hover { color: #555555; }
a:active { color: #333333; }

</style>
<body>

<?php $count = sizeof($query->result_array()); ?>

<table>

<tr><td><b>count:</b></td><td><b><?=$count?></b></td><td> [
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
<a href="/tracker/stats/<?=$order?>/<?=$num?>/0/<?=$lt?>">all</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/1/<?=$lt?>">SO</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/2/<?=$lt?>">SF</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/4/<?=$lt?>">SU</a>&nbsp;
<a href="/tracker/stats/<?=$order?>/<?=$num?>/3/<?=$lt?>">META</a>&nbsp;
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
        else if ($r['site'] == 3)
        {
            $name = "meta.stackoverflow.com";
            $abbrev = "META";
        }
        else if ($r['site'] == 4)
        {
            $name = "superuser.com";
            $abbrev = "SU";
        }
        else
        {
            $name = "UNKNOWN";
            $abbrev = "BAD!";
        }
        
        $name = $sites[$r['site']]['domain'];
        $abbrev = $sites[$r['site']]['domain'];
    
    
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
