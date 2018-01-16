
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<?php
//if (!empty($_POST)) {
//	echo 'POST PRESENT';
//	var_dump( $_POST['data'] );
//	die;
//}
var_dump( $_REQUEST );
echo '<br>';
var_dump( $_POST );
var_dump( $_POST[1]['name'] );
echo '<br>';
//var_dump($GLOBALS);
?>
<div id="resp">

</div>
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card text-white p-5 bg-primary">
                    <div class="card-body">
                        <h1 class="mb-4">search form</h1>
                        <form action="/" id="search_ebay" method="get">
                            <div class="form-group">
                                <label>Название товара</label>
                                <input id="name" name="name" type="text" class="form-control" placeholder="введите название"
                                       value="iPhone 7">
                            </div>
                            <div class="form-group d-flex">
                                <div class="col-sm-6 row">
                                    <label class="col-sm-6 col-form-label">мин цена</label>
                                    <div class="col-sm-6">
                                        <input id="price_min" name="price_min" type="text" class="form-control" placeholder="" value="1">
                                    </div>
                                </div>
                                <div class="col-sm-6 row">
                                    <label class="col-sm-6 col-form-label">макс цена</label>
                                    <div class="col-sm-6">
                                        <input id="price_max" name="price_max" type="text" class="form-control" placeholder="" value="10">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-secondary">Искать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">

	<?php
	/**
	 * Create the request object.
	 */
	$request = new Types\FindItemsAdvancedRequest();

	$request->keywords = 'iPhone 7';

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
		'value' => [ '100.00' ]
	] );

	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'MaxPrice',
		'value' => [ '1000.00' ]
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
			printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
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
			    printf("<img src='%s' alt='%s'>", $item->galleryURL, $item->title);
				printf(
					"(%s) %s: %s %.2f\n<br>",
					$item->itemId,
					$item->title,
					$item->sellingStatus->currentPrice->currencyId,
					$item->sellingStatus->currentPrice->value
				);
			}
		}
	}
	?>
</div>
<style>
    #resp{
        position: fixed;
        height: 200px;
        width: 80%;
        background: gray;
        z-index: -1;
    }
</style>
<script
        src="http://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $("form#search_ebay").submit(function(event) {
            event.preventDefault();
            var values = [];
            $("input").each(function() {
                values[$(this).attr("id")] = $(this).val();
            });
            console.log(values);
            var input = $("#name");
            // var inputs = {
            //     name : $("input#name).val,
            //     price_min : $("input#price_min).val,
            //     price_max : $("input#price_max).val
            // };

            $.ajax({
                type: "POST",
                data     : {data:values},
                // url: "YS_05-find-items-advanced.php",
                success: function(data) {
                    // debugger;
                    // input.val(data);
                    $('body').html(data);
                }
            });
        });
    });
</script>
</body>
</html>
