<tr><td colspan="5"><br><h3>reputation <a href="http://stackoverflow.com/questions/61553/track-your-reputation#485031"><font color="#cc2222" size="2">what is this?</font></a></h3></td></tr>
<?php

//print_r($answers);

$skipped = 0;
$shown   = 0;

//print_r($answers);

usort($posts, "scoresort");

foreach($posts as $post)
{
	$lastQ		= $post['newscore']	- $post['oldscore'];
	
	$post['text'] = preg_replace('/\\\\u(\d{4})/', '&#x$1;', $post['text']);

	if (!$lastQ && !$post['new'])
	{
		$skipped++;
		continue;
	}
	else
		$shown++;
	
	echo "<tr";
	if ($post['new']) echo " class=\"new\"";
	else if ($post['id'] == $post['qid']) echo " class=\"question\"";
	echo "><td class=\"".numclass($lastQ)."\" align=\"right\">&nbsp;&nbsp;";
	echo formatnum($lastQ, false);
	echo "&nbsp;&nbsp;</td><td colspan=\"2\" align=\"right\">&nbsp;&nbsp;".$post['newscore']."&nbsp;&nbsp;</td>";
	echo "<td><a ";
	if ($post['new']) echo "class=\"new\" ";
	echo "href=\"http://$site/questions/".$post['qid'].'/'.$post['id'].'#'.$post['id']."\">".$post['text']."</td></tr>\n";
	
}

echo "<tr><td colspan=\"3\" align=\"right\"><i>$skipped&nbsp;&nbsp;</i></td>";
echo "<td><i>unchanged not shown</i></td></tr>";

?>