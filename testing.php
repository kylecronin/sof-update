<?php

function _except($source, $exceptions) {
	foreach(array_keys($source) as $key)
		if (!in_array($key, $exceptions))
			$ret[$key] = $source[$key];

	return $ret;
}

$test = array('hello' => 'there', 'how' => 'are', 'you' => 'doing');

$lasd = _except($test, array('how'));

print_r($lasd);

?>