<?php

function numclass($num)
{
	if ($num > 0) return "up";
	if ($num < 0) return "down";
	return "nc";
}

function formatnum($num, $dispzero)
{
	if ($num > 0)
		return "+$num";
	else if ($num < 0)
		return "$num";
	else if ($dispzero)
		return "+0";
	else
		return "";
}



?>