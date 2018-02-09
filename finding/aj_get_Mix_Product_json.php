<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 07.02.2018
 * Time: 18:34
 */
require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
require_once 'functions.php';
//require_once   __DIR__ . '/../models/Product.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);
$dollar      = getDollarCourse();
// initialize ActiveRecord
ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
//	$cfg->set_model_directory( __DIR__ . '/../models');
	$cfg->set_connections( $connections );
} );

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
//	static $alias_attribute = array(
//		'alias_new_ebay_count' => 'new_ebay_count'
////		'alias_last_name' => 'last_name'
//	);
}

//
class Ebay extends ActiveRecord\Model {
	static $table_name = 'ebay';
	static $connection = 'production';
}

class Category extends ActiveRecord\Model {
	static $table_name = 'category';
	static $connection = 'production';
}

/*
 * CODE
 */


$product = new  Product;
$eBay    = new  Ebay;

$data = Product::all( array(
	'select'     => 'Product.*, `category`.name, ebay.datetimeleft',
	'from'       => '`Product`, `category`, `ebay`',
	'conditions' => 'Product.categoryID = category.citi_category_id AND Product.id = ebay.pid',
	'order'      => 'ebay.datetimeleft desc'
) );

foreach ( $data as &$result ) {

	$result = $result->to_array();

	$result['link'] = '<a target="_blank" href="' . $result['citilinkurl'] . '">' . $result['title'] . '</a>';

	$result['ebay_count']     = $result['last_approve_ebay_count'] . ' / ' . $result['last_all_ebay_count'];
	$result['ebay_count']     = ( '<button class="runIt">' . $result['ebay_count'] . '</button>' );
	$result['minmax_procent'] = ( $result['min_procent'] . ' / ' . $result['max_procent'] );
	unset( $result['last_approve_ebay_count'], $result['last_all_ebay_count'] );
	unset( $result['min_procent'], $result['max_procent'] );

}


echo ( json_encode( $data ) );


