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


	class Ebay extends ActiveRecord\Model {
		static $table_name = 'ebay';
		static $connection = 'production';
	}

	$ebay        = new Ebay;

	if ( $q['id'] ) {
		$ebayFind = $ebay::find( intval( $q['id'] ) );
		if ( $ebayFind ) {
			$ebayFind->update_attributes( $q );
			echo 'Updated';
		}

	} else {
		if ( $q['citilink_data'] ) {
			$q['citilink_data'] = htmlspecialchars( json_encode( $q['citilink_data'] ));
		}
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

		echo 'Created';
	}
} else {
	echo 'Nothing in POST';
}

