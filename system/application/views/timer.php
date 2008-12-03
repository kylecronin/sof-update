</table>
<br><small><i>scrape: <?=$pageload?>s&nbsp;&nbsp;&nbsp;process: <?=$dbprocess?>s</i></small>
<br><br>
<table border="0">
	<tr><td><small><b>last update:</b></small></td><td><small>
		<script type="text/javascript">showtime(<?=$dbitem->date."000"?>)</script>
	</small></td></tr>
	<tr><td><small><b>current time:</b></small></td><td><small>
		<script type="text/javascript">showtime(<?=time()."000"?>)</script>
	</small></td></tr>
</table>
<br><small><a href="http://stackoverflow.com/questions/61553/track-your-reputation">feedback welcome</a></small>