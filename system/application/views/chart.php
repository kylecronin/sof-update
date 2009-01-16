<!--[if IE]><script language="javascript" type="text/javascript" src="http://stackoverflow.com/Content/Js/third-party/excanvas.pack.js"></script><![endif]-->
<script type="text/javascript" src="http://stackoverflow.com/Content/Js/third-party/jquery.flot.pack.js"></script>
<div>
    <div id="placeholder" style="width:900px;height:450px;float:left;"></div>
<!-- <div><a href="all">see entire history</a></div> -->
</div>
<br>

<script id="source" language="javascript" type="text/javascript">
$(function () {
    var d = <?=$data?>

    var plot = $.plot($("#placeholder"),
           [d],
           { lines: { show: true },
             points: { show: true },
             //selection: { mode: "xy" },
             grid: { hoverable: true, clickable: true },
			xaxis: { mode: "time" } // thanks for catching this James Curran :)
             });

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(10);
    }

    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var d = item.datapoint[0],
                        r = item.datapoint[1],
					 	q = item.datapoint[2],
						a = item.datapoint[3],
						date = new Date(d);
						
						//pd = item.datapoint[4];
                    
                    showTooltip(item.pageX, item.pageY,
						"<b>"+r+"</b><br>"+q+" questions<br>"+a+" answers<br>"+date.toString());
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
    });

});
</script>
