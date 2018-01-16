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

<div class="py-5">
    <div class="container-fluid">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-2">
                <pre>
const C_AUTHORIZED_SELLER_ONLY = 'AuthorizedSellerOnly';
const C_AVAILABLE_TO = 'AvailableTo';
const C_BEST_OFFER_ONLY = 'BestOfferOnly';
const C_CHARITY_ONLY = 'CharityOnly';
const C_CONDITION = 'Condition';
const C_CURRENCY = 'Currency';
const C_END_TIME_FROM = 'EndTimeFrom';
const C_END_TIME_TO = 'EndTimeTo';
const C_EXCLUDE_AUTO_PAY = 'ExcludeAutoPay';
const C_EXCLUDE_CATEGORY = 'ExcludeCategory';
const C_EXCLUDE_SELLER = 'ExcludeSeller';
const C_EXPEDITED_SHIPPING_TYPE = 'ExpeditedShippingType';
const C_FEATURED_ONLY = 'FeaturedOnly';
const C_FEEDBACK_SCORE_MAX = 'FeedbackScoreMax';
const C_FEEDBACK_SCORE_MIN = 'FeedbackScoreMin';
const C_FREE_SHIPPING_ONLY = 'FreeShippingOnly';
const C_GET_IT_FAST_ONLY = 'GetItFastOnly';
const C_HIDE_DUPLICATE_ITEMS = 'HideDuplicateItems';
const C_LISTED_IN = 'ListedIn';
const C_LISTING_TYPE = 'ListingType';
const C_LOCAL_PICKUP_ONLY = 'LocalPickupOnly';
const C_LOCAL_SEARCH_ONLY = 'LocalSearchOnly';
const C_LOCATED_IN = 'LocatedIn';
const C_LOTS_ONLY = 'LotsOnly';
const C_MAX_BIDS = 'MaxBids';
const C_MAX_DISTANCE = 'MaxDistance';
const C_MAX_HANDLING_TIME = 'MaxHandlingTime';
const C_MAX_PRICE = 'MaxPrice';
const C_MAX_QUANTITY = 'MaxQuantity';
const C_MIN_BIDS = 'MinBids';
const C_MIN_PRICE = 'MinPrice';
const C_MIN_QUANTITY = 'MinQuantity';
const C_MOD_TIME_FROM = 'ModTimeFrom';
const C_OUTLET_SELLER_ONLY = 'OutletSellerOnly';
const C_PAYMENT_METHOD = 'PaymentMethod';
const C_RETURNS_ACCEPTED_ONLY = 'ReturnsAcceptedOnly';
const C_SELLER = 'Seller';
const C_SELLER_BUSINESS_TYPE = 'SellerBusinessType';
const C_SOLD_ITEMS_ONLY = 'SoldItemsOnly';
const C_START_TIME_FROM = 'StartTimeFrom';
const C_START_TIME_TO = 'StartTimeTo';
const C_TOP_RATED_SELLER_ONLY = 'TopRatedSellerOnly';
const C_VALUE_BOX_INVENTORY = 'ValueBoxInventory';
const C_WORLD_OF_GOOD_ONLY = 'WorldOfGoodOnly';
                </pre>
            </div>
            <div class="col-md-4">
                <div class="card text-white p-5 bg-primary">
                    <div class="card-body">
                        <h1 class="mb-4">search form</h1>
                        <form action="/" id="search_ebay" method="get">
                            <div class="form-group">
                                <label>Название товара (keywords)</label>
                                <input id="name" name="name" type="text" class="form-control"
                                       placeholder="введите название"
                                       value="iPhone 7, iPhone 6">
                            </div>
                            <div class="form-group d-flex">
                                <div class="col-sm-6 row">
                                    <label class="col-sm-6 col-form-label">мин цена</label>
                                    <div class="col-sm-6">
                                        <input id="price_min" name="price_min" type="text" class="form-control"
                                               placeholder="" value="100">
                                    </div>
                                </div>
                                <div class="col-sm-6 row">
                                    <label class="col-sm-6 col-form-label">макс цена</label>
                                    <div class="col-sm-6">
                                        <input id="price_max" name="price_max" type="text" class="form-control"
                                               placeholder="" value="1000">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Время лота от текущего (в часах)</label>
                                <input id="EndTimeTo" name="EndTimeTo" type="text" class="form-control"
                                       placeholder="время"
                                       value="24">
                            </div>

                            <button type="submit" class="btn btn-secondary">Искать</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Результаты выдачи</h3>
                <div id="resp">

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    pre {
        font-size: .5em;
    }

    #resp {
        /*position: fixed;*/
        /*height: 200px;*/
        /*width: 80%;*/
        /*background: gray;*/
        /*z-index: -1;*/
    }
</style>
<script
        src="http://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $("form#search_ebay").submit(function (event) {
            event.preventDefault();
            $('#resp').html('');
            var values = [];
            $("input").each(function () {
                values[$(this).attr("id")] = $(this).val();
            });
            console.log(values);
            var input = $("#name");
            // var inputs = {
            //     name : $("input#name).val,
            //     price_min : $("input#price_min).val,
            //     price_max : $("input#price_max).val
            // };
            $form = $(this);
            var serializedData = $form.serialize();
            console.log(serializedData);
            $.ajax({
                type: "POST",
                // data     : {data:values},
                data: serializedData,
                url: "YS_05-find-items-advanced.php",
                success: function (data) {
                    // debugger;
                    // input.val(data);
                    $('#resp').html(data);
                }
            });
        });
        setTimeout(function () {
                $("form#search_ebay").trigger('submit');

        },10000
        );

    });
</script>
</body>
</html>
