<?php 
// Report simple running errors
error_reporting(E_ERROR | E_PARSE);
define('INFINITY', 111);
?>
<?php
require_once('FloydWarshallKhan.php');
require_once('Keyboard.php');
?>
<?php

	$keyboard = new Keyboard();
	$graph = $keyboard->getGraph();
	$nodes = $keyboard->getNodes();
	$fw = new FloydWarshallKhan($graph, $nodes);

	$keyboard->set_pred($fw->get_pred());
	$sentence = 'find the optimum path for this sentence';
	echo $sentence;
	echo "\n";
	echo 'f→i→n→d→ →t→h→e→ o→p→t→i→m→u→m→ →p→a→t→h→ →f→o→r→ →t→h→i→s→ →s→e→n→t→e→n→c→e';
	echo "\n";
	$keyboard->findOptimumPath($fw, $sentence);

?>
