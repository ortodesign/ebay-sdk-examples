<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
error_reporting(0);
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
$q = json_decode(file_get_contents('php://input'),true)["id"];
//$q = json_decode(file_get_contents('php://input'),true);
//echo '</pre>';

require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
//require_once   __DIR__ . '/../models/Product.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);

// initialize ActiveRecord
ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
//	$cfg->set_model_directory( __DIR__ . '/../models');
	$cfg->set_connections( $connections );
} );

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
}
//class Keywords extends ActiveRecord\Model {
//	static $table_name = 'keywords';
//	static $connection = 'production';
//}

$product = new  Product;
//foreach ( $product::find(intval ($q)+1) as $k => $v ) {
//	echo json_encode($v);
//}
//echo $q;
echo $product::find($q)->to_json();

//	$citilink = getCitilinkJsonFromPyServ( $_POST['name'] );
//	$citilink = getCitilinkJsonFromFile( 'sumsung_galaxy_s7.json' );

//	require __DIR__ . '/../models/Product.php';
//	var_dump($citilink);
//	foreach ( $product::all() as $k => $v ) {
//		print_r( $k );
//		print_r( $v->shortName . ' : ' );
//		print_r( $v->id . ' : ' );
//		print_r( $v->url . '<br>' );
//		if (! $product::all( array( 'conditions' => array( 'citilinkID = ?', $v->id ) ) ) ) { // если айди нет в базе
//			$product::create( array(
//				'title'       => $v->shortName,
//				'citilinkURL' => $v->url,
//				'citilinkID'  => $v->id,
//				'keywordID'   => 1
//			) );
//		}
////		else {
////			$product::find('all', array( 'conditions' => array( 'citilinkID = ?', $v->id ) ) )->update_attributes(array(
////				'title'       => $v->shortName,
////				'citilinkURL' => $v->url,
////				'citilinkID'  => $v->id,
////				'keywordID'   => 1
////			) );
////		}
////		$product->save();
//
//	}

//	foreach ( $product::all() as $k => $v ) {
////                        print_r($k.'<br>');
//		print_r( $v->id . ' : ' . $v->title . '<br>' );
//	}


//}