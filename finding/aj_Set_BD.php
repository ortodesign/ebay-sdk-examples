<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
error_reporting( 0 );
$q = $_POST;

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

$product::find( intval( $q['id'] ) )->update_attributes( array(
	'title'         => $q['title'],
	'citilinkURL'   => $q['citilinkurl'],
	'citilinkID'    => $q['citilinkid'],
	'citilinkPrice' => $q['citilinkprice'],
	'keywordID'     => $q['keywordid'],
	'synonyms'      => $q['synonyms']
) );
echo '<pre>';
var_dump( $product::find( intval( $q['id'] ) )->attributes() );
echo '</pre>';
