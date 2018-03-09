<?php
error_reporting( 0 );
class JsParserException extends Exception {
}

function parse_jsobj( $str, &$data ) {
	$str = trim( $str );
	if ( strlen( $str ) < 1 ) {
		return;
	}

	if ( $str{0} != '{' ) {
		throw new JsParserException( 'The given string is not a JS object' );
	}
	$str = substr( $str, 1 );
//	print_r($str);
	/* While we have data, and it's not the end of this dict (the comma is needed for nested dicts) */
	while ( strlen( $str ) && $str{0} != '}' && $str{0} != ',' ) {
		/* find the key */
		if ( $str{0} == "'" || $str{0} == '"' ) {
			/* quoted key */
			list( $str, $key ) = parse_jsdata( $str, ':' );
		} else {
			$match = null;
			/* unquoted key */
			if ( ! preg_match( '/^\s*[a-zA-z_][a-zA-Z_\d]*\s*:/', $str, $match ) ) {
				throw new JsParserException( 'Invalid key ("' . $str . '")' );
			}
			$key = $match[0];
			$str = substr( $str, strlen( $key ) );
			$key = trim( substr( $key, 0, - 1 ) ); /* discard the ':' */
		}

		list( $str, $data[ $key ] ) = parse_jsdata( $str, '}' );
	}
	"Finshed dict. Str: '$str'\n";

	return substr( $str, 1 );
}

function comma_or_term_pos( $str, $term ) {
	$cpos = strpos( $str, ',' );
	$tpos = strpos( $str, $term );
	if ( $cpos === false && $tpos === false ) {
		throw new JsParserException( 'unterminated dict or array' );
	} else if ( $cpos === false ) {
		return $tpos;
	} else if ( $tpos === false ) {
		return $cpos;
	}

	return min( $tpos, $cpos );
}

function parse_jsdata( $str, $term = "}" ) {
	$str = trim( $str );


	if ( is_numeric( $str{0} . "0" ) ) {
		/* a number (int or float) */
		$newpos = comma_or_term_pos( $str, $term );
		$num    = trim( substr( $str, 0, $newpos ) );
		$str    = substr( $str, $newpos + 1 ); /* discard num and comma */
		if ( ! is_numeric( $num ) ) {
			throw new JsParserException( 'OOPSIE while parsing number: "' . $num . '"' );
		}

		return array( trim( $str ), $num + 0 );
	} else if ( $str{0} == '"' || $str{0} == "'" ) {
		/* string */
		$q      = $str{0};
		$offset = 1;
		do {
			$pos    = strpos( $str, $q, $offset );
			$offset = $pos;
		} while ( $str{$pos - 1} == '\\' ); /* find un-escaped quote */
		$data = substr( $str, 1, $pos - 1 );
		$str  = substr( $str, $pos );
		$pos  = comma_or_term_pos( $str, $term );
		$str  = substr( $str, $pos + 1 );

		return array( trim( $str ), $data );
	} else if ( $str{0} == '{' ) {
		/* dict */
		$data = array();
		$str  = parse_jsobj( $str, $data );

		return array( $str, $data );
	} else if ( $str{0} == '[' ) {
		/* array */
		$arr = array();
		$str = substr( $str, 1 );
		while ( strlen( $str ) && $str{0} != $term && $str{0} != ',' ) {
			$val = null;
			list( $str, $val ) = parse_jsdata( $str, ']' );
			$arr[] = $val;
			$str   = trim( $str );
		}
		$str = trim( substr( $str, 1 ) );

		return array( $str, $arr );
	} else if ( stripos( $str, 'true' ) === 0 ) {
		/* true */
		$pos = comma_or_term_pos( $str, $term );
		$str = substr( $str, $pos + 1 ); /* discard terminator */

		return array( trim( $str ), true );
	} else if ( stripos( $str, 'false' ) === 0 ) {
		/* false */
		$pos = comma_or_term_pos( $str, $term );
		$str = substr( $str, $pos + 1 ); /* discard terminator */

		return array( trim( $str ), false );
	} else if ( stripos( $str, 'null' ) === 0 ) {
		/* null */
		$pos = comma_or_term_pos( $str, $term );
		$str = substr( $str, $pos + 1 ); /* discard terminator */

		return array( trim( $str ), null );
	} else if ( strpos( $str, 'undefined' ) === 0 ) {
		/* null */
		$pos = comma_or_term_pos( $str, $term );
		$str = substr( $str, $pos + 1 ); /* discard terminator */

		return array( trim( $str ), null );
	} else {
		throw new JsParserException( 'Cannot figure out how to parse "' . $str . '" (term is ' . $term . ')' );
	}
}

$parsed = array();
//parse_jsobj($JSstrOBJ, $parsed);


if ( isset( $_GET['target'] ) ) {
	$url     = $_GET['target'];
	$proxy   = isset( $_GET['proxy'] ) ? $_GET['proxy'] : null;
	$timeout = isset( $_GET['timeout'] ) ? intval( $_GET['timeout'] ) : 10;
	//$proxyauth = 'user:password';

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );

	curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
	curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
	curl_setopt( $ch, CURLOPT_TCP_FASTOPEN, true );

//	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
//	curl_setopt($ch, CURLOPT_FAILONERROR, true);
//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	if ( $proxy ) {
//		print_r($proxy);
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_PROXY, $proxy );
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
		curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );
		//	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);
	}

	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // false for https
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );

	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_HEADER, 1 );
	$curl_scraped_page = curl_exec( $ch );
	curl_close( $ch );

	if ( $curl_scraped_page ) {
		$html = $curl_scraped_page;
		$dom  = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( $html );
		libxml_clear_errors();
//		var_dump($dom);
		$finder = new DOMXPath( $dom );
		try {

			//По сути ищем готовый json с данными из исходного кода страницы в тегах <script> и обрезаем строку регулярками.
			$rows = $finder->query( "//script[contains(text(),'var utag_data = ')]" )->item( 0 )->nodeValue;
			if ( isset($rows) ) {

				$pic = $finder->query( "(//span/@imgzoompic[contains(., 'ProductImageCompressAll1280')])[1]" )->item( 0 )->nodeValue;
				header( 'Content-Type: text/plain' );
				//		print_r( '{picUrl:"' );
				//		print_r( $pic );
				//		print_r( '",' );
				$NE_utag_data = ( substr( $rows, 12 + 12, strrpos( $rows, '}' ) - 12 - 12 + 1 ) );
				$NEtrim       = '{picUrl:"' . $pic . '",' . ( substr( $NE_utag_data, 0, strrpos( $NE_utag_data, 'search_scope' ) ) ) . '}';
				//		print_r( $NE_utag_data );
				//		print_r('\n---------------------------------------------------\n');
				//		print_r( $NEtrim );
				parse_jsobj( $NEtrim, $parsed );
				foreach ( $parsed as &$p ) {
					if ( is_array( $p ) ) {
						$p = (string) ( $p[0] );
					}
				}
				print_r( json_encode( $parsed ) );
			} else {
				die( json_encode(array('data'=>'DATA_NOT_PRESENT')) );
			}

		} catch ( Error $e ) {
			die( json_encode(array('data'=>'DATA_NOT_PRESENT_2'))  );
		}
	} else {
		die( json_encode(array('data'=>'DIE_PROXY')) );
	}
} else {
	die( json_encode(array('data'=>'NO_GET_PARAMETERS')) );
}

