<tr><td colspan="5"><br><h3>reputation <font color="AAAAAA"><small><i>(<a href="/tracker/chart/<?=$user?>"><font color="999999">chart</font></a>)</i></small></font></h3></td></tr>



<tr>
<td colspan="5">
<table><tr><td></td><td>
<div>
    <div id="placeholder" style="width:300px;height:200px;float:left;"></div>
</div>
</td></tr></table>
</td>
</tr>

<script id="source" language="javascript" type="text/javascript">
$(function () {
    var d = [<?=$data?>];

    var plot = $.plot($("#placeholder"),
           [d],
           { lines: { show: true },
             points: { show: false },
             //selection: { mode: "xy" },
             grid: { hoverable: false, clickable: false },
			xaxis: { mode: "time", timeformat: "%m/%d", ticks: 6},
            //yaxis: { ticks: "4"}
             });

});
</script>
