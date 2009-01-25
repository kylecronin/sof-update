</table>
<br><small><i>scrape: <?=$pageload?>s,&nbsp;&nbsp;&nbsp;process: <?=$dbprocess?>s</i></small>
<br><br>
<?php
	function RelativeTime($time, $now = false)
	{
		if ($time == 0)
			return "n/a";
	
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
			elseif ($shift < 604800):
				$diff = floor($shift / 60 / 60 / 24);
				$shift %= 86400;
				$term = "day";
			else:
				$diff = floor($shift / 60 / 60 / 24 / 7);
				$shift %= 604800;
				$term = "week";
			endif;

			if ($diff != 1) $term .= "s";
			$ret .= "$diff $term,&nbsp;&nbsp;";
		}
		
		return substr($ret, 0, strlen($ret)-13);
	}
?>
<table border="0">
	<tr><td align="right"><small><b>interval</b></small></td><td><small>&nbsp;</small></td><td><small><?=RelativeTime($dbitem->date)?></small></td></tr>
	<tr><td align="right"><small><b>last update</b></small></td><td><small>&nbsp;</small></td><td><small>
		<script type="text/javascript">showtime(<?=$dbitem->date."000"?>)</script>
	</small></td></tr>
	<tr><td align="right"><small><b>current time</b></small></td><td><small>&nbsp;</small></td><td><small>
		<script type="text/javascript">showtime(<?=time()."000"?>)</script>
	</small></td></tr>
</table>
<br><a href="http://stackoverflow.com/questions/61553/track-your-reputation"><font color="cc2222">feedback requested</font></a>