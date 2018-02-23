<?php
$ebayTime = new DateTime();
require_once( '../shopping/01-get-ebay-time.php' );
print_r( 'Время на eBay: ' . $ebayTime->format( DateTime::ISO8601 ) );

require_once( 'functions.php' );
$timeOffset = $_POST['EndTimeTo'] ? $_POST['EndTimeTo'] * 60 * 60 : 24 * 60 * 60; // offset Ending within
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
		Ebay::query('
		DELETE t1 FROM ebay_products t1
		  INNER JOIN
		  ebay_products t2
		WHERE
		(t1.product_id = t2.product_id) AND (t1.ebay_id = t2.ebay_id) and (t1.id < t2.id);
		');
//		var_dump(Ebay::connection()->query);
	}

}

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
	static $attr_protected = array( 'ebaydata' );
}

class Keywords extends ActiveRecord\Model {
	static $table_name = 'keywords';
	static $connection = 'production';
}


$product     = new Product;
$ebay        = new Ebay;
$ebayProduct = new EbayProduct;


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
foreach ( $product::all() as &$p ) {
	$request = new Types\FindItemsAdvancedRequest();

//	$request->keywords = $_POST['synonyms'] ? '(' . $_POST['synonyms'] . ')' : '(iPhone 7, iphone 6)';
	$request->keywords = '(' . $p->synonyms . ')';

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
	$priceIn = $p->citilinkprice;
//Проценты по дефолту мин - 50, макс - 80

//	$priceMin = $_POST['min_procent'] ? $priceIn * $_POST['min_procent'] / 100 : $priceIn * .5;
//	$priceMax = $_POST['max_procent'] ? $priceIn * $_POST['max_procent'] / 100 : $priceIn * .8; //$priceIn * $_POST['max_procent'] / 100;

	$priceMin = $p->min_procent ? $priceIn * $p->min_procent / 100 : $priceIn * .5;
	$priceMax = $p->max_procent ? $priceIn * $p->max_procent / 100 : $priceIn * .8; //$priceIn * $_POST['max_procent'] / 100;

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
	$response = $service->findItemsAdvanced( $request );

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
//echo "<br>==================\nResults \n==================\n<br>";

//	echo '<h4><small> Название:</small> ' . $p->synonyms . '</h4><h1><small> Синонимы: </small>' . $p->synonyms . '</h1>';
//	echo '<form id="formTableEbayResults">';
//	echo '<table id="tableEbayResults">';
	if ( $response->ack !== 'Failure' ) {
//		print_r( '<thead>' );
//		printf(
//			'<th></th><th>(%s)</th><th>(%s)</th><th>(%s)</th> <th>%s</th> <th>%s</th> <th>%s</th> <th>%s</th>',
//
//			'time', 'timeleft', 'itemId', 'title', 'ebayURL', 'currencyId', 'value'
//		);
//		print_r( '</thead>' );
//		print_r( '<tbody>' );
		foreach ( $response->searchResult->item as $item ) {
			try {
				$ebay::create( array(
					'id'           => $item->itemId,
					'pid'          => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
					'ebaydata'     => htmlspecialchars( json_encode( $item->toArray() ) ) //вся дата из ебея сюда жсоном
				) );
				$ebayProduct::create( array(
					'ebay_id'      => $item->itemId,
					'product_id'   => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
				) );
			} catch ( ActiveRecord\DatabaseException $exception ) {
				$ebay::find( intval( $item->itemId ) )->update_attributes( array(
					'pid'          => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
					'ebaydata'     => htmlspecialchars( json_encode( $item->toArray() ) ) //вся дата из ебея сюда жсоном
				) );
				$ebayProduct::create( array(
					'ebay_id'      => $item->itemId,
					'product_id'   => $p->id,
					'datetimeleft' => $item->listingInfo->endTime->format( DateTime::ISO8601 ),
				) );
			}



////			printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
//			echo '<tr id="ebayid_' . $item->itemId . '" data="' . htmlspecialchars( json_encode( $item->toArray() ) ) . '">';
//			$diff = $ebayTime->diff( $item->listingInfo->endTime );
//			printf(
//				'<td><input class="" type="checkbox"></td><td>%s</td><td>%s</td><td>(%s)</td> <td>%s</td> <td><a href="%s" target="_blank">link</a></td> <td>%s</td> <td>%.2f</td>',
////			$item->sellingStatus->timeLeft,
//				$item->listingInfo->endTime->format( DateTime::ISO8601 ),
////			$item->listingInfo->endTime->format('d h:m:s'),
////			$diff->format(DateTime::ISO8601),
//				$diff->format( '%h ч. %i м.' ),
//				$item->itemId,
//				$item->title,
//				$item->viewItemURL,
//				$item->sellingStatus->currentPrice->currencyId,
//				$item->sellingStatus->currentPrice->value
//			);
//			echo '</tr>';
		}
//		print_r( '</tbody>' );
//		echo '</table>';
//		echo '</form>';
	}
}
Ebay::removeDoubles();
echo 'Success';
?>
