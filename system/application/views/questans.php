<tr><td colspan="5"><br><h3><?=$name?></h3></td></tr>
<?php

$skipped = 0;
$shown = 0;
$new = false;

$this->db->query("BEGIN");

foreach ($stuff as $s)
{	
	$this->db->query("DELETE FROM Questions WHERE id = '$s[2]' AND reset = 1");

	$dbitem = $this->db->query("SELECT votes, accepted FROM Questions WHERE id = '$s[2]' ")->row();
	
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
		
		if ($lastQ != 0)
			$show = true;
	}
	else
	{
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
		$shown += 1;
		
	$this->db->query("INSERT INTO Questions VALUES('$s[3]', '$s[1]', '$s[2]', '$acreg', 1)");
	
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

$this->db->query("END");

echo "<tr><td colspan=\"3\" align=\"right\"><i>$skipped&nbsp;&nbsp;</i></td>";
echo "<td><i>unchanged not shown</i></td></tr>";

?>