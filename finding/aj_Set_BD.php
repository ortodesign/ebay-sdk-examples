<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
error_reporting( 0 );
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
$q = json_decode( file_get_contents( 'php://input' ), true );
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

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
}

$product = new Product;

echo $product::find( $q["id"] )->update_attributes( array(
	'title'       => $q['title'],
	'citilinkurl' => $q['citilinkurl'],
	'citilinkid'  => $q['citilinkid'],
	'keywordid'   => $q['keywordid'],
	'synonyms'    => $q['synonyms']
) );
