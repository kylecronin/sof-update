</table>
<br><small><i>scrape: [<?=$pageload?>s, <?=$page2load?>]&nbsp;&nbsp;&nbsp;process: <?=$dbprocess?>s</i></small>
<br><br>
<?php
	function RelativeTime($time, $now = false)
	{
		$time = (int) $time;
		$curr = $now ? $now : time();
		$shift = $curr - $time;
		
		$ret = "";
		
		while ($shift > 0)
		{
			if ($shift < 60):
				$diff = $shift;
				$shift = 0;
				$term = "second";
			elseif ($shift < 3600):
				$diff = floor($shift / 60);
				$shift %= 60;
				$term = "minute";
			elseif ($shift < 86400):
				$diff = floor($shift / 60 / 60);
				$shift %= 3600;
				$term = "hour";
			else:
				$diff = floor($shift / 60 / 60 / 24);
				$shift %= 86400;
				$term = "day";
			endif;

			if ($diff != 1) $term .= "s";
			$ret .= "$diff $term,&nbsp;&nbsp;";
		}
		
		return substr($ret, 0, strlen($ret)-13);
	}
?>
<table border="0">
	<tr><td><small><b>interval:</b></small></td><td><small><?=RelativeTime($dbitem->date)?></small></td></tr>
	<tr><td><small><b>last update:</b></small></td><td><small>
		<script type="text/javascript">showtime(<?=$dbitem->date."000"?>)</script>
	</small></td></tr>
	<tr><td><small><b>current time:</b></small></td><td><small>
		<script type="text/javascript">showtime(<?=time()."000"?>)</script>
	</small></td></tr>
</table>
<br><small><a href="http://stackoverflow.com/questions/61553/track-your-reputation">feedback welcome</a></small>