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
//}