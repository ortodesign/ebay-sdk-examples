<?php
ini_set('max_execution_time', 900);
require_once 'functions.php';
$spisok = getCitilinkJsonFromFile('spisok.json');
$url = 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php';
$all=array();
foreach ($spisok as &$s){
	usleep(rand(1000000,3000000));
	$data = array('target' => $s->citilinkURL);
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }
	array_push($all,$result);
}
//echo '<pre>';
print_r(json_encode($all));
//echo '</pre>';



