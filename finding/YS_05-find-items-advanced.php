<?php
echo '<br>';

$timeOffset = $_POST['EndTimeTo'] ? $_POST['EndTimeTo'] * 60 * 60 : 24 * 60 * 60; // offset Ending within
echo 'Смещение времени окончания аукциона: ' . $timeOffset / 60 / 60 . ' час.';
echo '<br>';
$dollar = getDollarCourse();
print('Dollar: ');
print($dollar);
print('<br>');
echo '<h5>POST - дата:</h5>';
var_dump( $_POST );
print('<br>');
echo '<br>';
/** convert time to iso8601 (ebay api format)
 *
 * @param int $offset смещение в секундах
 * @param int $precision
 *
 * @return bool|false|string
 */
function iso_8601_utc_time( $offset = 0, $precision = 0 ) {
	$time = gettimeofday();

	if ( is_int( $precision ) && $precision >= 0 && $precision <= 6 ) {
		$total         = (string) $time['sec'] . '.' . str_pad( (string) $time['usec'], 6, '0', STR_PAD_LEFT );
		$total_rounded = bcadd( $total, '0.' . str_repeat( '0', $precision ) . '5', $precision );
		@list( $integer, $fraction ) = explode( '.', $total_rounded );
		$format = $precision == 0
			? "Y-m-d\TH:i:s\Z"
			: "Y-m-d\TH:i:s," . $fraction . "\Z";

		return gmdate( $format, $integer + $offset );
	}

	return false;
}

function getDollarCourse() {
	$jso = file_get_contents( 'https://www.cbr-xml-daily.ru/daily_json.js', "r" );
	return json_decode( $jso )->Valute->USD->Value;
//                        response.Valute.USD.Value
//                        https://www.cbr-xml-daily.ru/daily_json.js
}

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
require __DIR__ . '/../vendor/autoload.php';

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

$request->keywords = $_POST['name'] ? '(' . $_POST['name'] . ')' : '(iPhone 7, iphone 6)';

/**
 * Search across two categories.
 * DVDs & Movies > DVDs & Blue-ray (617)
 * Books > Fiction & Literature (171228)
 */
//	$request->categoryId = [ '617', '171228' ];

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
$request->itemFilter[] = new Types\ItemFilter( [
	'name'  => 'MinPrice',
	'value' => [ $_POST['price_min'] ? $_POST['price_min'] : '100.00' ]
] );

$request->itemFilter[] = new Types\ItemFilter( [
	'name'  => 'MaxPrice',
	'value' => [ $_POST['price_max'] ? $_POST['price_max'] : '1000.00' ]
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
$request->paginationInput                 = new Types\PaginationInput();
$request->paginationInput->entriesPerPage = 10;
$request->paginationInput->pageNumber     = 1;

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

if ( $response->ack !== 'Failure' ) {
	foreach ( $response->searchResult->item as $item ) {
//			printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
		printf(
			"(%s) %s: %s %.2f\n<br>",
			$item->itemId,
			$item->title,
			$item->sellingStatus->currentPrice->currencyId,
			$item->sellingStatus->currentPrice->value
		);
	}
}

/**
 * Paginate through 2 more pages worth of results.
 */
$limit = min( $response->paginationOutput->totalPages, 3 );
for ( $pageNum = 2; $pageNum <= $limit; $pageNum ++ ) {
	$request->paginationInput->pageNumber = $pageNum;

	$response = $service->findItemsAdvanced( $request );

	echo "==================\nResults for page $pageNum\n==================\n<br>";

	if ( $response->ack !== 'Failure' ) {
		foreach ( $response->searchResult->item as $item ) {
//			    printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
			printf(
				"%s (%s) %s: %s %.2f\n<br>",
				$item->country,
				$item->itemId,
				$item->title,
				$item->sellingStatus->currentPrice->currencyId,
				$item->sellingStatus->currentPrice->value
			);
//				print('<pre>');
////				foreach ($item as $it) {
//				var_dump($item);
////                }
//				print('</pre>');
//				print('<br>');
		}
	}
}
?>
