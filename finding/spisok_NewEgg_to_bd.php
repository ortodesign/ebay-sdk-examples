<?php

$citySpisokIn = json_decode( file_get_contents( 'jsons/serj_newEgg.json', "r" ) );
$cityArray    = json_decode( file_get_contents( 'jsons/newegg001.json', "r" ));
//print_r( $citySpisokIn );
//echo '<pre>';
//var_dump($cityArray);
//echo '<br><br><br>';
//var_dump($citySpisokIn);
//echo '</pre>';
//print_r( json_encode( $citySpisokIn) );
//print_r( json_encode( $cityArray) );
require_once 'functions.php';
$dollar = getDollarCourse();
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
	static $attr_protected = array( 'ebaydata' );
}

class Category extends ActiveRecord\Model {
	static $table_name = 'category';
	static $connection = 'production';
}
$product = new Product;
$category = new Category;

foreach ( $citySpisokIn as $sin ) {
//	var_dump($sin);

//	id
//	our_name
//	min_lefttime
//	citilinkURL
//	synonyms
//	title
//	citilinkPrice
//	picture_url
//	last_all_ebay_count
//	last_approve_ebay_count
//	min_procent
//	max_procent
//	ebay_ids
//	citilinkID
//	ebay_price
//	citilink_data
//	categoryID

	foreach ( $cityArray as $ca ) {
		if (  $sin->citilinkURL == $ca->citilinkURL ) {
			print_r( $sin->citilinkURL );
			echo '<br>';
			$product::create(array(

				'our_name'     => $sin->our_name,
				'synonyms'     => $sin->synonyms,
				'citilinkURL'     => $sin->citilinkURL,
				'categoryID'     => isset($ca->categoryId) ? $ca->categoryId : null,
				'picture_url'     => isset($ca->picture_url) ? $ca->picture_url : null,
				'citilinkPrice'     => (int)($ca->citilinkPrice),
				'citilink_data'     => isset($ca->citilink_data) ? htmlspecialchars( json_encode( $ca ) ) : null //вся дата сюда жсоном
			));
//			$product->save();
			$category::create(array(
				'pid' => $product::last()->id,
				'name' => $sin->category,
				'citi_category_id' => isset($ca->categoryId) ? $ca->categoryId : null

			));
		}
	}
}

//echo '</pre>';