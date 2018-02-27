<?php $pagetitle = 'Парсим с newEgg';
include __DIR__ . '/../../header.php'; ?>

<body>
<div class="container">
    <div id="point">

    </div>
</div>
<?php include __DIR__ . '/../../jsLibs.php'; ?>
<script src="node_modules/he/he.js"></script>
<script>
<?php
$spisok  = file_get_contents(  __DIR__ . '/../../jsons/serj_newEgg.json', "r" );
?>

    var inJson = <?php echo $spisok; ?>;

    //fake object for eval
    var Web = {
        StateManager: {
            Cookies: {
                get: function () {
                    return false
                },
                Name: {
                    NVTC: '',
                    LOGIN: ''
                }
            }
        }
    };
inJson.forEach(function (item) {
    $.ajax({
        // url: 'parsers/php/parse_newEgg.php?' + 'target=' + 'https://www.newegg.com/Product/Product.aspx?Item=N82E16828840014', //item.citilinkURL,,
        url: 'parsers/php/parse_newEgg.php?' + 'target=' + item.citilinkURL,
        // data: data,
        success: function (data) {
            var ev = eval('(' + data + ')');
            console.log(ev);
            // $('#point').append('<div>'+ev.product_model+'</div>');
            $('#point').append('<h3>' + he.decode(String(ev.product_title)) + '</h3>');
            $('#point').append('<img src="' + ev.picUrl + '" style="width: 800px">');
            $('#point').append('<div class="h1">' + ev.product_sale_price + '</div>');
        },
        // dataType: dataType
    });
});

    ////var inJson = $.parseJSON('<?php ////echo $spisok; ?>////');
    //var inJson = <?php //echo $spisok; ?>//;
    //var alreadyJson = <?php //echo $already; ?>//;
    //var inJsonURLs = inJson.map(function (i) {
    //    return i.citilinkURL;
    //});
    //var alreadyURLs = alreadyJson.map(function (i) {
    //    return i.productUrl;
    //});
    //// $('#point').html(inJson);
    //function sleep(miliseconds) {
    //    var currentTime = new Date().getTime();
    //
    //    while (currentTime + miliseconds >= new Date().getTime()) {
    //    }
    //}
    ////
    //// console.log(inJsonURLs);
    //// console.log(alreadyURLs);
    //var t = [];
    //// var son = JSON.parse(inJson);
    //// $('#point').append('[');
    //inJson.forEach(function (item) {
    //    // alreadyURLs.forEach(function (aItem) {
    //    // console.log(item.citilinkURL);
    //    if (!(alreadyURLs.indexOf(item.citilinkURL) !== -1)) {
    //        t.push(item);
    //        // sleep(300);
    //        $.ajax({
    //            url: 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php?' + 'target=' + item.citilinkURL,
    //            // data: data,
    //            success: function (data) {
    //                console.log(data);
    //                $('#point').append(data);
    //            },
    //            // dataType: dataType
    //        });
    //
    //    };
    //
    //
    //    // console.log(item);
    //
    //    // });
    //});
    //// console.log(t);
    //
    //// $('#point').append(']');
    //
    //
    //// $('#point').html(inJson);
</script>


</body>
</html>


