<?php
error_reporting( 0 );
require_once __DIR__ . '/../../functions.php';
require_once __DIR__ . '/../../../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

$dollar = getDollarCourse();

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);

ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
	$cfg->set_connections( $connections );
} );


class Parsed_citi extends ActiveRecord\Model {
	static $table_name = 'parsed_citi';
	static $connection = 'production';
//	static $attr_protected = array( 'ebaydata' );
}

$parsed_citi = new Parsed_citi;


//class Category extends ActiveRecord\Model {
//	static $table_name = 'category';
//	static $connection = 'production';
//}
//$category = new Category;

$citiCategories = json_decode( file_get_contents( __DIR__ . '/../in/categories_citi_001.json', "r" ) );
$thelist        = [ '000' ];

if ( $handle = opendir( __DIR__ . '/../jsons_out/' ) ) {
	while ( false !== ( $file = readdir( $handle ) ) ) {
		if ( $file != "." && $file != ".." && strtolower( substr( $file, strrpos( $file, '.' ) + 1 ) ) == 'json' ) {
			array_push( $thelist, $file );
		}
	}
	closedir( $handle );
}


//print_r( $thelist );
setlocale( LC_CTYPE, 'en_AU.utf8' );

header( "Content-type: text/plain" );

$big = [];
foreach ( $citiCategories as $v ) {
	$f = json_decode( file_get_contents( __DIR__ . '/../jsons_out/' . $thelist[ intval( $v->num ) ], "r" ), false );
	foreach ( $f as $k ) {
//		print_r( $k->shortName . "\n" );
		array_push( $big, $k );
	}
//	$big = array_merge($big, $f);
}

$brands = [ 'GIGABYTE', 'SAPPHIRE', 'POWERCOLOR', 'JETSTREAM', 'ThinkPad' ];

foreach ( $big as &$item ) {
//echo '<pre>';
	$item->{"pre_syn"} = '';
	$item->{"non_cyr"} = '';
	$s                 = $item->shortName;
	if ( $item->categoryId == 580 || $item->categoryId == 29 || $item->categoryId == 3 ) { //Если в винтах SSD
		$s   = str_replace( $brands, '', $s );
		$s   = preg_replace( '/\x{0413}\x{0431}/iu', 'GB', $s ); //Регулярка для "Гб"
		$s   = preg_replace( '/\x{0422}\x{0411}/iu', 'TB', $s ); //Регулярка для "Тб"
		$re  = '/(.*\s)([A-Z0-9-\/]{8,})([\s|,].*$)/iu'; //Регулярка для поиска маркировки
		$str = $s;
		preg_match( $re, $str, $matches, PREG_OFFSET_CAPTURE, 0 );
		$item->{"pre_syn"} = trim( $matches[2][0] );
//		print_r( $item->pre_syn . "\t" );
//		print_r( $item->non_cyr . "\t" );
	}

	$s = @iconv( "UTF-8", "ASCII//IGNORE//TRANSLIT", $s ); //Кириллицу в топку
	$s = preg_replace( '/\s{2,}/iu', ' ', $s ); //Двойные пробелы в топку
	$s = preg_replace( '/,.$/iu', ' ', $s ); //Запятая в конце в топку
	$s = preg_replace( '/,\s\//iu', ' ', $s ); //Запятая пробел слэш в топку
	$s = preg_replace( '/,\s-/iu', ' ', $s ); //Запятая пробел дефис в топку


	$sk   = explode( ',', $s );
	$ssyn = '';
	foreach ( $sk as &$vv ) {
		if ( strlen( $vv ) > 6 && trim( $vv ) != $item->pre_syn) {
			$ssyn = $ssyn . ',' . trim( $vv );
		}
	}
	$item->pre_syn = $item->pre_syn .','. trim($ssyn,',');
	$item->pre_syn = trim($item->pre_syn,',');
	print_r( $item->pre_syn . "\n" );


	$item->{"non_cyr"} = $s;

	//	print_r( preg_replace( '/[\x{0410}-\x{042F}]+.*[\x{0410}-\x{042F}]+/iu', '*', $item->shortName ). "\t" );
	print_r( "\t" . $item->categoryId . "\t" . $item->categoryName . "\t" . ( $item->pre_syn ? $item->pre_syn : ' - ' ) . "\t" . ( $item->non_cyr ? $item->non_cyr : ' - ' ) . "\t" . $item->shortName . "\n" );

		if ( $parsed_citi::exists( intval( $item->id ) ) ) {
			echo $item->id. " - already exist \n";
		} else 	{
//			$parsed_citi::create( json_decode( json_encode( $item ), true ) );
			$parsed_citi::create( array(
				'id'           => intval($item->id),
				'categoryName' => $item->categoryName,
				'url'          => $item->url,
				'price'        => $item->price,
				'brandName'    => $item->brandName,
				'shortName'    => $item->shortName,
				'categoryId'   => $item->categoryId,
				'pre_syn'      => $item->pre_syn,
				'non_cyr'      => $item->non_cyr,

			));
		}

//	foreach ( $item as $field => $val ) {
//		print_r( '[' . $field . ']' . $val . "\t" );
//	}
	print_r( "\n" );
//	print_r( $v->num . "\t" );
//	print_r( $v->cat_name . "\t\t" );
//	print_r( $v->url );
//echo '</pre>';
}