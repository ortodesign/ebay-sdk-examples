<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
error_reporting( 0 );
//$json = file_get_contents('php://input');
//$json = file_get_contents('php://input');
//$dataObject = json_decode($json);
//$dataArray = json_decode($json, true);
//header('Content-type: application/json');

//echo '<pre>';
//echo( json_decode(file_get_contents('php://input'),true)["title"]);
//echo '<br>\n\r';
//echo( json_decode(key($_POST))->title );
//echo( key($_POST) );
//$q = json_decode(key($_POST)->id);
$q = json_decode( file_get_contents( 'php://input' ), true )["id"];
//$q = json_decode(file_get_contents('php://input'),true);
//echo '</pre>';

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

//$data = EbayProduct::all( array(
//	'select'     => 'Product.*, `category`.name, ebay.datetimeleft',
//	'from'       => '`Product`, `category`, `ebay`',
//	'conditions' => 'Product.categoryID = category.citi_category_id AND Product.id = ebay.pid',
//	'order'      => 'ebay.datetimeleft desc'
//) );

$eb = EbayProduct::find_by_sql( '
	SELECT *
    FROM ebay_products,Product,ebay
    WHERE (ebay_products.product_id = Product.id) AND (ebay_products.ebay_id = ebay.id) AND (ebay_products.datetimeleft > "2018-02-21T13:39:07+0000")
  	# GROUP BY ebay_id
    ORDER BY ebay_products.datetimeleft ASC 
    ' );

foreach ( $eb as &$e ) {
//	$tmpstr = str_replace('&quot;', '"', $e->ebaydata);
	$tmp_ebay      = json_decode( html_entity_decode( $e->ebaydata ), true ); //парсим строку JSON из БД, с тем чтобы на выход отдать общий валидный json
	$tmp_ebay_citi = json_decode( html_entity_decode( $e->citilink_data ), true ); //парсим строку JSON из БД, с тем чтобы на выход отдать общий валидный json
	$e             = $e->to_array();
	$e['ebaydata'] = $tmp_ebay;
	$e['citilink_data'] = $tmp_ebay_citi;
}
echo json_encode( $eb );

//$eb = $ebay::all( array(
//	'order' => 'ebay.datetimeleft asc'
//) );
//
//foreach ( $eb as &$e ) {
////	$tmpstr = str_replace('&quot;', '"', $e->ebaydata);
//	$tmp_ebay      = json_decode( html_entity_decode( $e->ebaydata ), true );
//	$e             = $e->to_array();
//	$e['ebaydata'] = $tmp_ebay;
//}
//echo json_encode( $eb );