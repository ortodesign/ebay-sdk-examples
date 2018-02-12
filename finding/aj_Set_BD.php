<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
//error_reporting( 0 );
$q = $_POST;
if ( $q ) {

	if ( $q['data'] ) {
		$q = $q['data'];
	}

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
		static $attr_protected = array('ebaydata');
	}

	class Ebay extends ActiveRecord\Model {
		static $table_name = 'ebay';
		static $connection = 'production';
	}

	$product = new Product;
	$ebay = new Ebay;

	if ( $q['id'] ) {
		$productFind = $product::find( intval( $q['id'] ) );
		if ( $productFind ) {
			$productFind->update_attributes( $q );
//			$productFind->update_attributes( array(
//				'title'         => $q['title'],
//				'citilinkURL'   => $q['citilinkurl'],
//				'citilinkID'    => $q['citilinkid'],
//				'citilinkPrice' => $q['citilinkprice'],
//				'categoryID'    => $q['categoryid'],
//				'synonyms'      => $q['synonyms']
//			) );
			echo 'Updated';
//			echo '<pre>';
//			var_dump( $product::find( intval( $q['id'] ) )->attributes() );
//			echo '</pre>';
			if ($q['ebaydata']) {
				echo '<pre>';
//				var_dump($q['ebaydata']);
//				$ebaydata = json_decode($q['ebaydata']);
				$ebaydata = $q['ebaydata'];

//				if ( $q['ebaydata'] ) {
//					$q['ebaydata'] = htmlspecialchars( json_encode( $q['ebaydata'] ) );
//				};
				foreach ( $ebaydata as $e ) {
					var_dump($e['e']);
					$ebay::create(array(
						'id'         => $e['e']['itemId'],
						'ebaydata'   => htmlspecialchars( json_encode( $e ) )
//						'citilinkID'    => $q['citilinkid'],
//						'citilinkPrice' => $q['citilinkprice'],
//						'categoryID'    => $q['categoryid'],
//						'synonyms'      => $q['synonyms']
					));
				}
				echo '</pre>';


			}
		}

	} else {
		$product::create( $q );
//		$product::create(  );
		echo 'Created';
//		echo '<pre>';
//		var_dump( $q );
//		echo '</pre>';

	}
} else {
	echo 'Nothing in POST';
}
//if ( $q['ebaydata'] ) {
//	echo '<pre>';
//	var_dump( $q['ebaydata'] );
//	echo '</pre>';
//}


//$product::find( intval( $q['id'] ) )->update_attributes( array(
//	'title'         => $q['title'],
//	'citilinkURL'   => $q['citilinkurl'],
//	'citilinkID'    => $q['citilinkid'],
//	'citilinkPrice' => $q['citilinkprice'],
//	'keywordID'     => $q['keywordid'],
//	'synonyms'      => $q['synonyms']
//) );
