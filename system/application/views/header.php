<html>
<head>
	<title><?=$sitename?> Update Script</title>
	<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<style type="text/css">
	  	.new { 	background-color: green;
		 	 	color: white;
				font-weight: bold;}
		.question { 	background-color: cccccc;}
		.up { 	background-color: green;
				color: white;
				font-weight: bold;}
		.down { background-color: red;
				color: white;
				font-weight: bold;}
		.nc {    /*background-color: white; */
				/*color: black;*/ }
		a.new:link {color: #FFFFFF;}
		a.new:visited { color: #FFFFFF; }
		a.new:hover { color: #AAAAAA; }
		a.new:active { color: #CCCCCC;}
		a:link { color: #000000; }
		a:visited { color: #000000; }
		a:hover { color: #555555; }
		a:active { color: #333333;}
		
	</style>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> 
    <script type="text/javascript" src="/content/js/master.js?v=4087"></script>

    <!--[if IE]><script language="javascript" type="text/javascript" src="/Content/Js/third-party/excanvas.pack.js"></script><![endif]-->
    <script type="text/javascript" src="/Content/Js/third-party/jquery.flot.pack.js"></script>
    <script src="/Content/Js/third-party/date.js" type="text/javascript"></script>

	<script type="text/javascript">
	function showtime(t)
	{
		if (t == 0)
		{
			document.write("never");
			return;
		}
		
	
		var currentTime = new Date(t);
		var month = currentTime.getMonth() + 1;
		var day = currentTime.getDate();
		var year = currentTime.getFullYear();
		var hours = currentTime.getHours();
		var minutes = currentTime.getMinutes();
		var seconds = currentTime.getSeconds();
		document.write();
		if (minutes < 10){
		minutes = "0" + minutes;
		}
		if (seconds < 10){
		seconds = "0" + seconds;
		}
		document.write(month + "/" + day + "/" + year + "&nbsp;&nbsp;&nbsp;" + hours + ":" + minutes + ":" + seconds + " ");
		if(hours > 11){
		document.write("PM");
		} else {
		document.write("AM");
		}
	}
	</script>
	




    
</head>
<body>
    
	<table cellpadding="0" cellspacing="0" border="0">

	
