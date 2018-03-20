<?php
ini_set( 'max_execution_time', 3600 );
$ebayTime = new DateTime();
require_once( '../shopping/01-get-ebay-time.php' );
print_r( 'Время на eBay: ' . $ebayTime->format( DateTime::ISO8601 ) );

require_once( 'functions.php' );
$timeOffset = isset( $_POST['EndTimeTo'] ) ? $_POST['EndTimeTo'] * 60 * 60 : 24 * 60 * 60; // offset Ending within
$dollar     = getDollarCourse();

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

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

class EbayProduct extends ActiveRecord\Model {
	static $table_name = 'ebay_products';
	static $connection = 'production';
}

class Ebay extends ActiveRecord\Model {
	static $table_name = 'ebay';
	static $connection = 'production';

	public static function removeDoubles() {
		Ebay::query( '
		DELETE t1 FROM ebay_products t1
		  INNER JOIN
		  ebay_products t2
		WHERE
		(t1.product_id = t2.product_id) AND (t1.ebay_id = t2.ebay_id) AND (t1.id < t2.id);
		' );
//		var_dump(Ebay::connection()->query);
	}

}

//class Product extends ActiveRecord\Model {
//	static $table_name = 'Product';
//	static $connection = 'production';
//	static $attr_protected = array( 'ebaydata' );
//}

//class Keywords extends ActiveRecord\Model {
//	static $table_name = 'keywords';
//	static $connection = 'production';
//}


//$product     = new Product;
$ebay        = new Ebay;
$ebayProduct = new EbayProduct;


class Parsed_citi extends ActiveRecord\Model {
	static $table_name = 'parsed_citi';
	static $connection = 'production';
//	static $attr_protected = array( 'ebaydata' );
}

$parsed_citi = new Parsed_citi;


?>


<?php

$config = require __DIR__ . '/../configuration.php';

/**
 * The namespaces provided by the SDK.
 */

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

/**
 * Create the service object.
 */
$service = new Services\FindingService( [
	'credentials' => $config['production']['credentials'],
	'globalId'    => Constants\GlobalIds::US
] );
?>

<?php
/**
 * Create the request object.
 */

$usedPostTypes = [ 'citilinkPrice', 'synonyms', 'id', 'EndTimeTo' ];

//foreach ( $product::all() as &$p ) {
//	print_r( $p->id );
//	echo stripcslashes( '\t' );
//	print_r( $p->synonyms );
//	echo stripcslashes( '\t' );
//	print_r( $p->citilinkprice );
//	echo '<br>';
//}

if (isset($_GET) & $_GET['part'] == 'true') {

	$qd = array_map( function($value) { return (int)$value; }, $_POST['ids'] );
	$parse_object = $parsed_citi::find($qd);

//	$parse_object = ( isset( $_POST ) & $_POST['data'] ) ? $_POST['data'] : die( 'no data' );

} else {

	$parse_object = $parsed_citi::all();
}
foreach ( $parse_object as &$p ) {
	$request = new Types\FindItemsAdvancedRequest();

//	$request->keywords = $_POST['synonyms'] ? '(' . $_POST['synonyms'] . ')' : '(iPhone 7, iphone 6)';
//	$request->keywords = '(' . ( isset( $p->pre_syn ) ? ( $p->pre_syn . ',' ) : "" ) . ( isset( $p->non_cyr ) ? $p->non_cyr : "" ) . ')';
	$request->keywords = '(' . ( isset( $p->pre_syn ) ? $p->pre_syn : "" ) . ')';
//	print_r( '$request->keywords: ' );
//	print_r( $request->keywords );
	/**
	 * Search across two categories.
	 * DVDs & Movies > DVDs & Blue-ray (617)
	 * Books > Fiction & Literature (171228)
	 */
//	$request->categoryId = [ '617', '171228' ];
//	$request->categoryId = [ '9355' ];

	/**
	 * Filter results to only include auction items or auctions with buy it now.
	 */
	$itemFilter            = new Types\ItemFilter();
	$itemFilter->name      = 'ListingType';
	$itemFilter->value[]   = 'Auction';
	$itemFilter->value[]   = 'AuctionWithBIN';
	$request->itemFilter[] = $itemFilter;

	/**
	 * Add additional filters to only include items that fall in the price range of $1 to $10.
	 *
	 * Notice that we can take advantage of the fact that the SDK allows object properties to be assigned via the class constructor.
	 */

//	$priceIn = $_POST['citilinkprice'] ? $_POST['citilinkprice'] : 1000;
//	$priceIn = $p->citilinkprice;
	$priceIn = $p->price / $dollar;
//Проценты по дефолту мин - 50, макс - 80

//	$priceMin = $_POST['min_procent'] ? $priceIn * $_POST['min_procent'] / 100 : $priceIn * .5;
//	$priceMax = $_POST['max_procent'] ? $priceIn * $_POST['max_procent'] / 100 : $priceIn * .8; //$priceIn * $_POST['max_procent'] / 100;

	$priceMin = isset( $p->min_procent ) ? $priceIn * $p->min_procent / 100 : $priceIn * .5;
	$priceMax = isset( $p->max_procent ) ? $priceIn * $p->max_procent / 100 : $priceIn * .8; //$priceIn * $_POST['max_procent'] / 100;

//	$_POST['min_procent'] ? $_POST['min_procent'] :
	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'MinPrice',
		'value' => [ (string) $priceMin ]
//	'value' => [ $_POST['citilinkprice'] ? (string)($_POST['citilinkprice'] * 0.5) : '100.00' ]
	] );

	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'MaxPrice',
		'value' => [ (string) $priceMax ]
//	'value' => [ $_POST['citilinkprice'] ? (string)($_POST['citilinkprice'] * 0.8) : '100.00' ]
	] );


	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'EndTimeTo',
		'value' => [ iso_8601_utc_time( $timeOffset ) ]
	] );

	/**
	 * Sort the results by current price.
	 */
//$request->sortOrder = 'CurrentPriceHighest';
	$request->sortOrder = 'EndTimeSoonest';

	/**
	 * Send the request.
	 */
//	print_r( '$request: ' ); print_r( $request );

	$response = $service->findItemsAdvanced( $request );
	if ( ! $response ) {
		die( 'no response' );
	}
	if ( isset( $response->errorMessage ) ) {
		foreach ( $response->errorMessage->error as $error ) {
			printf(
				"%s: %s\n\n",
				$error->severity === Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
				$error->message
			);
		}
	}

	/**
	 * Output the result of the search.
	 */
//printf(
//	"%s items found over %s pages.\n\n",
//	$response->paginationOutput->totalEntries,
//	$response->paginationOutput->totalPages
//);
//printf( '<script> gl.eresp = %s ; </script>', $response->paginationOutput->totalEntries );

	if ( $response->ack !== 'Failure' ) {
		foreach ( $response->searchResult->item as $item ) {
//			echo '<pre>';
//			var_dump( $item );
//			echo '</pre>';
			if ( $ebay::exists( intval( $item->itemId ) ) ) {
				$ebay::find( intval( $item->itemId ) )->update_attributes( array(
					'pid'       => $p->id,
					'our_price' => intval( $p->price / $dollar ),
//					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
					'ebaydata'  => htmlspecialchars( json_encode( $item->toArray() ) ) //вся дата из ебея сюда жсоном
				) );
				$ebayProduct::create( array(
					'ebay_id'      => $item->itemId,
					'product_id'   => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
				) );
			} else {
				$ebay::create( array(
					'id'        => $item->itemId,
					'pid'       => $p->id,
					'our_price' => intval( $p->price / $dollar ),
//					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
					'ebaydata'  => htmlspecialchars( json_encode( $item->toArray() ) ) //вся дата из ебея сюда жсоном
				) );
				$ebayProduct::create( array(
					'ebay_id'      => $item->itemId,
					'product_id'   => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
				) );
			}


		}
	}
}
Ebay::removeDoubles();
echo 'Success';
?>
