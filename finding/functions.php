<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 22.01.2018
 * Time: 14:55
 */

//class f {

/** convert time to iso8601 (ebay api format)
 *
 * @param int $offset смещение в секундах
 * @param int $precision
 *
 * @return bool|false|string
 */
function iso_8601_utc_time( $offset = 0, $precision = 0 ) {
	$time = gettimeofday();
	if ( is_int( $precision ) && $precision >= 0 && $precision <= 6 ) {
		$total         = (string) $time['sec'] . '.' . str_pad( (string) $time['usec'], 6, '0', STR_PAD_LEFT );
		$total_rounded = bcadd( $total, '0.' . str_repeat( '0', $precision ) . '5', $precision );
		@list( $integer, $fraction ) = explode( '.', $total_rounded );
		$format = $precision == 0
			? "Y-m-d\TH:i:s\Z"
			: "Y-m-d\TH:i:s," . $fraction . "\Z";

		return gmdate( $format, $integer + $offset );
	}

	return false;
}


function getCategoriesFromJsonFile() {
	$cats = file_get_contents( 'category.json', "r" );

	return json_decode( $cats );
}


function getDollarCourse() {
	$jso = file_get_contents( 'https://www.cbr-xml-daily.ru/daily_json.js', "r" );

	return json_decode( $jso )->Valute->USD->Value;
}


function getCitilinkJsonFromPyServ( $query ) {
	$citilinkJson = file_get_contents( 'http://localhost:8081/?' . urlencode( $query ), "r" );

	return json_decode( $citilinkJson );
}
function getCitilinkJsonFromFile( $query ) {
	$citilinkJson = file_get_contents( $query, "r" );

	return json_decode( $citilinkJson );
}

function object_to_array($data)
{
	if(is_array($data) || is_object($data))
	{
		$result = array();

		foreach($data as $key => $value) {
			$result[$key] = $this->object_to_array($value);
		}

		return $result;
	}

	return $data;
}


/*
 * EBAY functions from forums etc...
 */

function timeLeft($target)
{
	$return_value = "";
	$diff = $target['endTime']->diff($target['timeStamp']);
	$doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals

	$format = array();
	if($diff->y !== 0) {
		$format[] = "%y ".$doPlural($diff->y, "year");
	}
	if($diff->m !== 0) {
		$format[] = "%m ".$doPlural($diff->m, "month");
	}
	if($diff->d !== 0) {
		$format[] = "%d ".$doPlural($diff->d, "day");
	}
	if($diff->h !== 0) {
		$format[] = "%h ".$doPlural($diff->h, "hour");
	}
	if($diff->i !== 0) {
		$format[] = "%i ".$doPlural($diff->i, "minute");
	}
	if($diff->s !== 0) {
		if(!count($format)) {
			return "less than a minute ago";
		} else {
			$format[] = "%s ".$doPlural($diff->s, "second");
		}
	}

	// Prepend 'since ' or whatever you like from the calling function
	return $diff->format(implode (" ",$format));

}


//}