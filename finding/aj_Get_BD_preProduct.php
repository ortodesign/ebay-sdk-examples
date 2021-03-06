<?php
error_reporting( 0 );
require_once( '../shopping/01-get-ebay-time.php' );
$dt        = new DateTime();
$dt        = $ebayTime;
$curEbTime = $ebayTime->format( DateTime::ISO8601 );
if ( isset( $_GET['plushours'] ) ) {
	$dt->modify( '+' . $_GET['plushours'] . ' hours' );
} else {
	$dt->modify( '+24 hours' );
}

$deadLine = $dt->format( DateTime::ISO8601 );
$q        = json_decode( file_get_contents( 'php://input' ), true )["id"];


require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);

ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
	$cfg->set_connections( $connections );
} );

class EbayProduct extends ActiveRecord\Model {
	static $table_name = 'ebay_products';
	static $connection = 'production';
}

class Ebay extends ActiveRecord\Model {
	static $table_name = 'ebay';
	static $connection = 'production';
}

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
	static $attr_protected = array( 'ebaydata' );
}

$product     = new Product;
$ebay        = new Ebay;
$ebayProduct = new EbayProduct;

class Parsed_citi extends ActiveRecord\Model {
	static $table_name = 'parsed_citi';
	static $connection = 'production';
}

$parsed_citi = new Parsed_citi;

class Pre_product extends ActiveRecord\Model {
	static $table_name = 'oops';
	static $connection = 'production';
}

$pre_product = new Pre_product;

class Doubles_products extends ActiveRecord\Model {
	static $table_name = 'doubles';
	static $connection = 'production';
}


$doubles_products = new Doubles_products;
//echo '<pre>';
//var_dump($doubles_products::all());
//echo '</pre>';
$cc = 'ONLY_HAS_OUT';

switch ( $cc ) {
	case 'ONLY_HAS_OUT':
		$sqlQuery =
//			'
//		SELECT
//  (SELECT categoryName FROM citi_cats
//  WHERE category_pid = citi_cats.categoryId) AS categoryname,  oops.*
//FROM oops
//#   , ebay_products
//# WHERE oops.older_pid = ebay_products.product_id
//
//# GROUP BY older_pid
//ORDER BY finded DESC
//
//		';

			'
 SELECT
  (SELECT categoryName FROM citi_cats
   WHERE category_pid = citi_cats.categoryId) AS categoryname,  oops.*
 FROM oops, ebay_products
 WHERE oops.older_pid = ebay_products.product_id
 GROUP BY older_pid
 ORDER BY finded DESC
 
 # HAVING count(*) >= 0
';
		break;
	default:
		$sqlQuery = 'SELECT * FROM oops';
}

$pc = $pre_product::find_by_sql( $sqlQuery );

foreach ( $pc as &$kkv ) {
	$kkv = $kkv->to_array();
}

$isApproved = isset( $_GET['approved'] ) ? 'AND (ebay.approved = "1")' : '';

$doubles = $doubles_products::find_by_sql( '
SELECT product_id, ebay_id ,COUNT(*) count
FROM  ebay_products
GROUP BY  product_id HAVING  COUNT(*) >= 1
# ORDER BY product_id;
' );

foreach ( $doubles as &$kv ) {
	$kv = $kv->to_array();
}

$km = array_map( function ( $v ) {
	return $v['product_id'];
}, $doubles );
//$km = array_filter($doubles,function($a){
//	return $a["count"];
//});
//
//echo '<pre>';
////var_dump($kv);
//var_dump($doubles);
//echo '</pre>';


//$eb = $ebay::find_by_sql( '
//SELECT *
//FROM ebay_products,parsed_citi,ebay
//WHERE (ebay_products.product_id = parsed_citi.id)   AND (ebay_products.ebay_id = ebay.id)
//     # AND (ebay_products.datetimeleft > "' . $curEbTime . '") AND (ebay_products.datetimeleft < "' . $deadLine . '")' . $isApproved . '
//  	# GROUP BY ebay_id
//    ORDER BY ebay_products.datetimeleft ASC
//    ' );
//
//foreach ( $eb as &$e ) {
//	$tmp_ebay      = json_decode( html_entity_decode( $e->ebaydata ), true ); //парсим строку JSON из БД, с тем чтобы на выход отдать общий валидный json
//	$e             = $e->to_array();
//	$e['ebaydata'] = $tmp_ebay;
//	if ( in_array( $e['product_id'], $km ) ) {
//		$idx        = array_search( intval( $e['product_id'] ), $km );
//		$e['count'] = $doubles[ $idx ]['count'];
//	} else {
//		$e['count'] = 0;
//	}
//}


//echo 'size = '.count( $eb);
//echo 'size = ' . sizeof( $eb );
//echo json_encode( $eb );
echo json_encode( $pc );
