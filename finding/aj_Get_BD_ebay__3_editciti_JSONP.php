<?php

//ini_set('display_errors', 1);
error_reporting( 0 );

require_once( '../shopping/01-get-ebay-time.php' );
require_once( 'functions.php' );
$dollar    = getDollarCourse();
$dt        = new DateTime();
$dt        = $ebayTime;
$curEbTime = $ebayTime->format( DateTime::ISO8601 );
//$dt->modify( '+1 hours' );
if ( isset( $_GET['plushours'] ) ) {
	$dt->modify( '+' . $_GET['plushours'] . ' hours' );
} else {
	$dt->modify( '+24 hours' );
}
$deadLine = $dt->format( DateTime::ISO8601 );

$q = json_decode( file_get_contents( 'php://input' ), true )["id"];

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


$isApproved = isset( $_GET['approved'] ) ? 'AND (ebay.approved = "1")' : '';
$eb         = EbayProduct::find_by_sql( '
	SELECT TRUNCATE(oops.price / ' . $dollar . ',0) as oprice, oops.*,ebay_products.*,ebay.* FROM oops,ebay_products,ebay
    WHERE (ebay_products.product_id = oops.older_pid) AND (ebay_products.ebay_id = ebay.id) 
    AND (ebay_products.datetimeleft > "' . $curEbTime . '") AND (ebay_products.datetimeleft < "' . $deadLine . '")' . $isApproved . '  
  	# GROUP BY ebay_id
    ORDER BY ebay_products.datetimeleft ASC 
    ' );

foreach ( $eb as &$e ) {
	$tmp_ebay      = json_decode( html_entity_decode( $e->ebaydata ), true ); //парсим строку JSON из БД, с тем чтобы на выход отдать общий валидный json
	$e             = $e->to_array();
	$e['ebaydata'] = $tmp_ebay;
}
if ( isset( $_GET['callback'] ) ) {
	header( 'Content-type: application/x-javascript' );
	echo $_GET['callback'] . "([" . json_encode( $eb ) . "])";
} else {
	echo json_encode( $eb );
}