<tr><td colspan="5"><br><h3>answers <font color="AAAAAA"><small><i>(<a href="http://stackoverflow.com/questions"><font color="999999">answer</font></a>)</i></small></font></h3></td></tr>
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

if ($count != ($shown+$skipped))
  {
    echo "<tr><td colspan=\"3\" align=\"right\"><i>";
    echo $count-$skipped;
    echo "&nbsp;&nbsp;</i></td>";
    echo "<td><i>unable to be tracked</i></td></tr>";
  }

?>
<?php
/*

$skipped = 0;
$shown = 0;
$new = false;

$this->db->query("BEGIN");

foreach ($stuff as $s)
{
	//$query = "SELECT votes, accepted FROM Questions WHERE id = '$s[2]'";
	//$dbitem = $db->query($query);//->fetch(PDO::FETCH_ASSOC);
	//$dbitem = mysql_fetch_assoc(mysql_query($query));
	
	$dbitem = $this->db->query("SELECT votes, accepted FROM Questions WHERE id = '$s[2]'")->row();
	
	$acreg = preg_match('/answered-accepted" title/s', $s[0]);
	
	$show = false;
	
	if ($dbitem)
		$accepted = $acreg - $dbitem->accepted;
	else
		$accepted = $acreg;
		
	if (!$dbitem || $accepted != 0)
		$show = true;
	
	if ($dbitem)
	{
		$lastQ = $s[1] - $dbitem->votes;
		
		// I have no idea what took me this long to do this
		if ($accepted || $lastQ)
			$this->db->query("UPDATE Questions SET votes = '$s[1]', accepted = '$acreg' WHERE id = '$s[2]'");
		if ($lastQ != 0)
			$show = true;
		//$new = false;
	}
	else
	{
		$this->db->query("INSERT INTO Questions VALUES('$s[3]', '$s[1]', '$s[2]', '$acreg')");
		$lastQ = 0;
		$show = true;
		$new = true;
	}
	
	if (!$show)
	{
		$skipped += 1;
		continue;
	}
	else
	{
		$shown += 1;
	}
	
	echo "<tr";
	if ($new) echo " class=\"new\"";
	echo "><td class=\"".numclass($lastQ)."\" align=\"right\">&nbsp;&nbsp;";
	echo formatnum($lastQ, false);
	echo "&nbsp;&nbsp;</td><td class=\"".numclass($accepted)."\">&nbsp;&nbsp;";
	if ($accepted > 0)
		echo "+A";
	else if ($accepted < 0)
		echo "-A";
	else if ($acreg) echo "A";
	echo "&nbsp;&nbsp;</td><td align=\"right\">&nbsp;&nbsp;$s[1]&nbsp;&nbsp;</td>";
	echo "<td><a ";
	if ($new) echo "class=\"new\" ";
	echo "href=\"http://stackoverflow.com/questions/$s[2]/\">$s[3]</td></tr>\n";
	

}

echo "<tr><td colspan=\"3\" align=\"right\"><i>$skipped&nbsp;&nbsp;</i></td>";
echo "<td><i>unchanged not shown</i></td></tr>";

if ($count != count($stuff))
  {
    echo "<tr><td colspan=\"3\" align=\"right\"><i>";
    echo $count-count($stuff);
    echo "&nbsp;&nbsp;</i></td>";
    echo "<td><i>unable to be tracked</i></td></tr>";
  }





$this->db->query("END");



*/

?>