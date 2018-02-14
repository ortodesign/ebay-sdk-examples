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

//TODO Добить в БД до timestamp 4 bytes (https://stackoverflow.com/posts/28289860/revisions)
	ActiveRecord\DateTime::$DEFAULT_FORMAT = 'iso8601';
//	ActiveRecord\DateTime::$DEFAULT_FORMAT = 'Y-m-d H:i:s';


	class Product extends ActiveRecord\Model {
		static $table_name = 'Product';
		static $connection = 'production';
		static $attr_protected = array( 'ebaydata' );
	}

	class EbayProduct extends ActiveRecord\Model {
		static $table_name = 'ebay_products';
		static $connection = 'production';
	}

	class Ebay extends ActiveRecord\Model {
		static $table_name = 'ebay';
		static $connection = 'production';
	}

	$product     = new Product;
	$ebay        = new Ebay;
	$ebayProduct = new EbayProduct;

//	$format = 'Y-m-d H:i:s';

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
			if ( $q['ebaydata'] ) {
				echo '<pre>';
				$ebaydata = $q['ebaydata'];
				foreach ( $ebaydata as $e ) {
//					echo '<br>1) ';
//					echo $e['listingInfo']['endTime'];
//					echo '<br>2) ';
////					echo DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', strtotime($e['listingInfo']['endTime']));
//					echo '<br>';
					$ebayProduct::create( array(
						'ebay_id'    => $e['itemId'],
						'product_id' => $q['id'],
					) );
//					$ebay::query(
//						'INSERT INTO ebay (id, pid, datetimeleft, ebaydata) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE id=?, pid=?, datetimeleft=?, ebaydata=?',
//						array(
//							'id'           => $e['itemId'],
//							'pid'          => $q['id'],
//							'datetimeleft' => $e['listingInfo']['endTime'],
////						'datetimeleft' => $e['listingInfo']['endTime']->format(DateTime::ISO8601),
////						'datetimeleft' => $e['listingInfo']['endTime']['date'],
////						'datetimeleft' => DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $e['listingInfo']['endTime']),
////						'datetimeleft' => strtotime($e['listingInfo']['endTime']),
//							'ebaydata'     => htmlspecialchars( json_encode( $e ) ) //вся дата из ебея сюда жсоном
//						)
//					);

					try {
						$ebay::create( array(
							'id'           => $e['itemId'],
							'pid'          => $q['id'],
							'datetimeleft' => $e['listingInfo']['endTime'],
//						'datetimeleft' => $e['listingInfo']['endTime']->format(DateTime::ISO8601),
//						'datetimeleft' => $e['listingInfo']['endTime']['date'],
//						'datetimeleft' => DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $e['listingInfo']['endTime']),
//						'datetimeleft' => strtotime($e['listingInfo']['endTime']),
							'ebaydata'     => htmlspecialchars( json_encode( $e ) ) //вся дата из ебея сюда жсоном
						) );
					} catch ( ActiveRecord\DatabaseException $exception ) {
//						echo 'Oh no! I`m a moron!' . $exception;
						$ebay::find( intval( $e['itemId'] ) )->update_attributes( array(
//							'id'           => $e['itemId'],
							'pid'          => $q['id'],
							'datetimeleft' => $e['listingInfo']['endTime'],
//						'datetimeleft' => $e['listingInfo']['endTime']->format(DateTime::ISO8601),
//						'datetimeleft' => $e['listingInfo']['endTime']['date'],
//						'datetimeleft' => DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $e['listingInfo']['endTime']),
//						'datetimeleft' => strtotime($e['listingInfo']['endTime']),
							'ebaydata'     => htmlspecialchars( json_encode( $e ) ) //вся дата из ебея сюда жсоном
						) );

					}


				}
				echo '</pre>';


			}
		}

	} else {
		$product::create( $q );
		if ( $q['ebaydata'] ) {
			echo '<pre>';
			$ebaydata = $q['ebaydata'];
			foreach ( $ebaydata as $e ) {
				$ebay::create( array(
					'id'           => $e['itemId'],
					'pid'          => $q['id'],
					'datetimeleft' => strtotime( $e['listingInfo']['endTime'] ),
					'ebaydata'     => htmlspecialchars( json_encode( $e ) ) //вся дата из ебея сюда жсоном
				) );
			}
			echo '</pre>';


		}

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

