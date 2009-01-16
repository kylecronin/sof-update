<tr><td colspan="5"><h3>overview</h3></td></tr>
<?php

function overview($new, $old, $caption)
{
	$diff = $new - $old;
	
	if ($diff > 0) $style = "up";
	else if ($diff < 0) $style = "down";
	else $style = "nc";
	
	if ($diff >= 0) $diff = "+$diff";

	?>
	<tr>
		<td class="<?=$style?>" align="right">&nbsp;&nbsp;<?=$diff?>&nbsp;&nbsp;</td>
		<td colspan="2" align="right">&nbsp;&nbsp;<?=$new?>&nbsp;&nbsp;</td>
		<td><?=$caption?></td>
	</tr>
	<?php
}

overview(count($questions), $dbitem->questions, "questions");
overview($answercount, $dbitem->answers, "answers");
overview($rep, $dbitem->rep, "reputation");
overview($badge, $dbitem->badges, "badges");

?>