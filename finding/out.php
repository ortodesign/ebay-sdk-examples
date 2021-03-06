<?php

if (isset($_GET['plushours'])) {
	$plushours = '?plushours='.$_GET['plushours'];
} else {
	$plushours = NULL;
}
?>
<!doctype html>
<!--suppress ALL -->
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" href="styles_dop.css">
    <title>Выдача</title>
</head>
<body>

<!--<h2 style="position: fixed;left: 20px;top: 20px;">Обновилось раз: <span id="counter"></span></h2>-->
<div class="container" id="ttt">
</div>


<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.js"></script>
<script src="jquery.dataTables.min.js"></script>
<script src="dataTables.bootstrap4.min.js"></script>
<script src="libs2/moment.js"></script>
<script src="libs2/locale/ru.js"></script>

<script>
    var refreshed = 0;

    function getContent() {
        $.get('aj_Get_BD_ebay.php<?php echo $plushours; ?>' , function (data, status) {
            $out = $.parseJSON(data);
            // console.log($out);
            $target = $('#ttt');
            var $cont = $('<div class="row">');
            for (var i = 0; i < $out.length; i++) {
                if (i % 3 == 0) {
                    var row = $('<div class="d-flex  justify-content-center align-items-stretch flex-wrap" style="min-height: 40rem">');
                    $cont.append(row);
                }

                var $props = '';
                // $.each($out[i].citilink_data.additions.properties, function (k, v) {
                //     $props += '<b>' + k + ': </b>' + v + '<br />';
                // });
                $props = $out[i].description ? $out[i].description : '';
                var $price = Math.ceil($out[i].our_price ? $out[i].our_price : $out[i].ebaydata.sellingStatus.currentPrice.value);
                var $procent = Math.ceil(100 - (parseInt($out[i].ebaydata.sellingStatus.currentPrice.value) * 100) / parseInt($out[i].citilinkprice));
                var $pic = $out[i].picture_url ? $out[i].picture_url : ($out[i].pic_url ? $out[i].pic_url : ( $out[i].citilink_data.productPictureUrl ? $out[i].citilink_data.productPictureUrl : $out[i].ebaydata.galleryURL));
                var timeLeft = moment($out[i].ebaydata.listingInfo.endTime, moment.ISO_8601, 'ru').format('MMM, DD [в <b style="color:red">]HH:mm[</b>]');
                row.append(`

                    <div class="card" style="width: 20rem;margin: 1rem;" >
        <div style="height: 30rem; ">

                    <div class="card-img-top" style="height: 20rem;background: #babfc5 url(${$pic}) 100% 100% no-repeat;
                    background-size: auto auto;background-position: center;background-size: cover;"></div>
                </div>
                                <p class="" style="text-align:right;border: 1px dashed #c5bc61"><small>Ставки до:</small> ${timeLeft} </p>

                        <div class="card-body d-flex flex-column justify-content-between" style="padding: 1rem; height: 100%">
                            <h4 class="card-title">${($out[i].our_name ? $out[i].our_name : '')}</h4>
                            <p class="card-text" style=" white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">${$props}</p>
                            <div>
                                <span class="card-title h4"><small>Цена:</small> <b style="color:#d00000;">${$price}</b> &dollar;</span> <span class="h4" style="float:right;"><small>Скидка:</small><b style="color:#2b542c;"> – ${$procent}%</b></span>
                                <a href="${$out[i].ebaydata.viewItemURL}" target="_blank" class="btn btn-primary">Смотреть детали</a> <span class="h5"  style="float:right;"><strike>${$out[i].citilinkprice}</strike>$</span>
                            </div>
                        </div>
                    </div>

                `);
            }
            // <a href="${$out[i].ebaydata.viewItemURL}" target="_blank" class="btn btn-primary">Смотреть детали</a>

            //         <img class="card-img-top embed-responsive" src="${$out[i].ebaydata.galleryURL}" alt="Card image">

            // <p class="card-text">${$out[i].citilink_data.additions.properties["Особенности"]}.</p>

            // console.log($cont);
            $target.html($cont);
            refreshed++;
            // $('#counter').text(refreshed);

        });

    }

    getContent();

    setInterval(function () {
        getContent();
        console.clear();
        console.log('refreshed: ',refreshed);
    }, 8000);

</script>

</body>
</html>
