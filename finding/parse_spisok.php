<?php
ini_set('max_execution_time', 1200);
//echo 'start';
require_once 'functions.php';
$spisok = getCitilinkJsonFromFile('jsons/spisok0222.json');
//$url = 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php';
$url = 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php?';
$all=array();
echo '[';
//echo file_get_contents( $url . urlencode( 'target='. 'https://www.citilink.ru/catalog/photo_and_video/dslr/396878/' ), "r" );
foreach ($spisok as &$s){
	sleep(rand(2,5));
	echo file_get_contents( $url . urlencode( 'target='. $s->citilinkURL.'&'.mt_rand()  ), "r" );




//	$data = array('target' => $s->citilinkURL);
//	$options = array(
//		'http' => array(
//			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//			'method'  => 'POST',
//			'content' => http_build_query($data)
//		)
//	);
//	$context  = stream_context_create($options);
//	$result = file_get_contents($url, false, $context);
//	if ($result === FALSE) { echo 'error parse'; }
//	array_push($all,$result);
//	echo (json_encode($result));
//	echo ',';



}
echo ']';

//echo '<pre>';
//echo (json_encode($all));
//echo '</pre>';
//echo 'end';



