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


$product  = new  Product;
$category = new  Category;

//				foreach ( $product::find( 'all', array( 'order' => 'id desc' ) ) as $k => $v ) {
//обратный ордер по id подхватывается из js datatable

//$json = json_encode($product->all());
//echo($product->all()->to_json());
//echo $product::find(1)->to_json();
$data = $product::find('all');
foreach ($data as &$result) {
	$result = $result->to_array();
}
echo(json_encode($data));
//foreach ( $product->all() as $k => $v ) {
//	echo '<tr id="cid' . $v->citilinkid . '" data-id="' . $v->id . '" data-cid="' . $v->citilinkid . '" data-all="' . htmlspecialchars( json_encode( $v->attributes() ) ) . '">';
//	print_r( '<td>' . $v->id . '</td>' );
//	print_r( '<td>' . $category::all( array(
//			'conditions' => array( 'citi_category_id = ?', $v->categoryid )
//		) )[0]->name . '</td>' );
//	print_r( '<td>' . $v->min_lefttime . '</td>' );
//	print_r( '<td>' . $v->ebay_price . '</td>' );
//	print_r( '<td>' . $v->citilinkprice . '</td>' );
////					print_r( '<td>' . $category::find( $v->categoryid )[0]->name . '</td>' );
//	print_r( '<td><a target="_blank" href="' . $v->citilinkurl . '">' . $v->title . '</a></td>' );
////					print_r( '<td>' . $v->citilinkid . '</td>' );
//	print_r( '<td class="synonyms">' . $v->synonyms . '</td>' );
//	print_r( '<td><button class="runIt">' . $v->last_approve_ebay_count . ' / ' . $v->last_all_ebay_count . '</button></td>' );
//	print_r( '<td>' . $v->min_procent . ' / ' . $v->max_procent . '</td>' );
////					print_r( '<td class="wrapword">' . $v->citilinkurl . '</td>' );
////					print_r( '<td>' . '<button>&times;</button>' . '</td>' );
//	print_r( '<td>' . '<button class="runCiti">&rarr;</button>' . '</td>' );
//	echo '</tr>';
//}
