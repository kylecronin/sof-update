<tr><td colspan="5"><br><h3>answers <font color="AAAAAA"><small><i>(<a href="http://<?=$site?>/questions"><font color="999999">answer</font></a>)</i></small></font></h3></td></tr>
<?php

//print_r($answers);

$skipped = 0;
$shown   = 0;

//print_r($answers);


foreach($answers as $answer)
{

	$lastQ		= $answer['newscore']	- $answer['oldscore'];
	$accepted	= $answer['newacc']		- $answer['oldacc'];
	
	if (!$lastQ && !$accepted)
	{
		$skipped += $answer['qty'];
		continue;
	}
	else
		$shown += $answer['qty'];
		
	
	
	echo "<tr";
	if ($answer['new']) echo " class=\"new\"";
	echo "><td class=\"".numclass($lastQ)."\" align=\"right\">&nbsp;&nbsp;";
	echo formatnum($lastQ, false);
	echo "&nbsp;&nbsp;</td><td class=\"".numclass($accepted)."\">&nbsp;&nbsp;";
	if ($accepted > 0)
		echo "+A";
	else if ($accepted < 0)
		echo "-A";
	else if ($answer['newacc']) echo "A";
	echo "&nbsp;&nbsp;</td><td align=\"right\">&nbsp;&nbsp;".$answer['newscore']."&nbsp;&nbsp;</td>";
	echo "<td><a ";
	if ($answer['new']) echo "class=\"new\" ";
	echo "href=\"http://stackoverflow.com/questions/".$answer['id']."/\">".$answer['text']."</a>";
	if ($answer['qty'] != 1) echo " (".$answer['qty'].")";
	echo "</td></tr>\n";
	
}


echo "<tr><td colspan=\"3\" align=\"right\"><i>$skipped&nbsp;&nbsp;</i></td>";
echo "<td><i>unchanged not shown</i></td></tr>";

if ($count > ($shown+$skipped))
  {
    echo "<tr><td colspan=\"3\" align=\"right\"><i>";
    echo $count-$skipped;
    echo "&nbsp;&nbsp;</i></td>";
    echo "<td><i>unable to be tracked</i></td></tr>";
  }

?>