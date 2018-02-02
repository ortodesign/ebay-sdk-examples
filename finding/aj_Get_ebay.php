<?php
require_once( 'functions.php' );
$timeOffset = $_POST['EndTimeTo'] ? $_POST['EndTimeTo'] * 60 * 60 : 24 * 60 * 60; // offset Ending within
$dollar = getDollarCourse();
echo '<h5>POST - дата:</h5>';
var_dump( $_POST );
print( '<br>' );

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
//require_once   __DIR__ . '/../models/Product.php';

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

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
}
class Keywords extends ActiveRecord\Model {
	static $table_name = 'keywords';
	static $connection = 'production';
}

$product = new  Product;
if ( isset( $_POST['citilink'] ) ) {
//	$citilink = getCitilinkJsonFromPyServ( $_POST['name'] );
	$citilink = getCitilinkJsonFromFile( 'sumsung_galaxy_s7.json' );

//	require __DIR__ . '/../models/Product.php';
//	var_dump($citilink);
	foreach ( $citilink as $k => $v ) {
		print_r( $k );
		print_r( $v->shortName . ' : ' );
		print_r( $v->id . ' : ' );
		print_r( $v->url . '<br>' );
		if (! $product::all( array( 'conditions' => array( 'citilinkID = ?', $v->id ) ) ) ) { // если айди нет в базе
			$product::create( array(
				'title'       => $v->shortName,
				'citilinkURL' => $v->url,
				'citilinkID'  => $v->id,
				'citilinkPrice'  => $v->price,
				'keywordID'   => 1
			) );
		}
		else {
			$product::find(array( 'citilinkID' => $v->id ) )->update_attributes(array(
				'title'       => $v->shortName,
				'citilinkURL' => $v->url,
//				'citilinkID'  => $v->id,
				'citilinkPrice'  => $v->price,
				'keywordID'   => 1
			) );
			$product->save();
		}

	}

//	foreach ( $product::all() as $k => $v ) {
////                        print_r($k.'<br>');
//		print_r( $v->id . ' : ' . $v->title . '<br>' );
//	}
}
print( '</pre><br>' );
//echo '<h5>Соответствие категорий:</h5><pre>';
//var_dump( getCategoriesFromJsonFile() );
//print( '</pre><br>' );

echo '<br>';


?>


<?php
/**
 * Copyright 2016 David T. Sadler
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Include the SDK by using the autoloader from Composer.
 */

/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 */
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
$request = new Types\FindItemsAdvancedRequest();

$request->keywords = $_POST['synonyms'] ? '(' . $_POST['synonyms'] . ')' : '(iPhone 7, iphone 6)';

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
//$request->itemFilter[] = new Types\ItemFilter( [
//	'name'  => 'MinPrice',
//	'value' => [ $_POST['price_min'] ? $_POST['price_min'] : '100.00' ]
//] );
//
//$request->itemFilter[] = new Types\ItemFilter( [
//	'name'  => 'MaxPrice',
//	'value' => [ $_POST['price_max'] ? $_POST['price_max'] : '1000.00' ]
//] );

$request->itemFilter[] = new Types\ItemFilter( [
	'name'  => 'MaxPrice',
	'value' => [ $_POST['citilinkprice'] ? (string)($_POST['citilinkprice'] * 0.2) : '100.00' ]
] );


$request->itemFilter[] = new Types\ItemFilter( [
	'name'  => 'EndTimeTo',
	'value' => [ iso_8601_utc_time( $timeOffset ) ]
] );

/**
 * Sort the results by current price.
 */
$request->sortOrder = 'CurrentPriceHighest';

/**
 * Limit the results to 10 items per page and start at page 1.
 */
//$request->paginationInput                 = new Types\PaginationInput();
//$request->paginationInput->entriesPerPage = 10;
//$request->paginationInput->pageNumber     = 1;

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
printf(
	"%s items found over %s pages.\n\n",
	$response->paginationOutput->totalEntries,
	$response->paginationOutput->totalPages
);

echo "<br>==================\nResults for page 1\n==================\n<br>";
echo '<table>';
if ( $response->ack !== 'Failure' ) {
	foreach ( $response->searchResult->item as $item ) {
//			printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
		echo '<tr class="ebayid_'.$item->itemId.'">';
		printf(
			'<td><input class="" type="checkbox"></td><td>(%s)</td><td>(%s)</td> <td>%s</td> <td>%s</td> <td>%.2f</td>',
//			$item->sellingStatus->timeLeft,
			$item->listingInfo->endTime->format('d/m/Y h:m:s'),
			$item->itemId,
			$item->title,
			$item->sellingStatus->currentPrice->currencyId,
			$item->sellingStatus->currentPrice->value
		);
		echo '</tr>';
	}
}
echo '</table>';

/**
 * Paginate through 2 more pages worth of results.
 */
//$limit = min( $response->paginationOutput->totalPages, 3 );
//for ( $pageNum = 2; $pageNum <= $limit; $pageNum ++ ) {
//	$request->paginationInput->pageNumber = $pageNum;
//
//	$response = $service->findItemsAdvanced( $request );
//
//	echo "==================\nResults for page $pageNum\n==================\n<br>";
//
//	if ( $response->ack !== 'Failure' ) {
//		foreach ( $response->searchResult->item as $item ) {
////			    printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
//			printf(
//				"%s (%s) %s: %s %.2f\n<br>",
//				$item->country,
//				$item->itemId,
//				$item->title,
//				$item->sellingStatus->currentPrice->currencyId,
//				$item->sellingStatus->currentPrice->value
//			);
////				print('<pre>');
//////				foreach ($item as $it) {
////				var_dump($item);
//////                }
////				print('</pre>');
////				print('<br>');
//		}
//	}
//}
?>
