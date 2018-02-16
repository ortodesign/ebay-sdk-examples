<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 16.02.2018
 * Time: 16:47
 */

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
    <link rel="stylesheet" href="node_modules/handsontable/dist/handsontable.full.css">
    <title>Выдача</title>
</head>
<body>
<h2>Обновилось раз: <span id="counter"></span>
</h2>
<div class="container" id="ttt">
</div>


<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="libs2/moment.js"></script>
<script src="libs2/locale/ru.js"></script>

<script>
    var refreshed = 0;

    function getContent() {
        $.get("aj_Get_BD_ebay.php", function (data, status) {
            $out = $.parseJSON(data);
            console.log($out);
            $target = $('#ttt');
            var $cont = $('<div>');
            for (var i = 0; i < $out.length; i++) {
                if (i % 3 == 0) {
                    var row = $('<div class="d-flex  justify-content-center align-items-stretch">');
                    $cont.append(row);
                }
                row.append(`

                    <div class="card" style="width: 20rem;margin: 1rem;" >
                    <div style="height: 30rem; overflow: hidden">
                        <img class="card-img-top embed-responsive" src="${$out[i].ebaydata.galleryURL}" alt="Card image">
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between" style="padding: 1rem; height: 100%">
                            <h4 class="card-title">${$out[i].our_name}</h4>
                            <p class="card-text">${$out[i].citilink_data.additions.properties["Особенности"]}.</p>
                            <div>
                            <h4 class="card-title"><small>Последняя ставка:</small> ${$out[i].ebaydata.sellingStatus.currentPrice.value} &dollar;</h4>
                            <a href="${$out[i].ebaydata.viewItemURL}" target="_blank" class="btn btn-primary">Смотреть детали</a>
                            </div>
                        </div>
                    </div>

                `);
            }
            console.log($cont);
            $target.html($cont);
            refreshed++;
            $('#counter').text(refreshed);

        });

    }

    getContent();

    setInterval(function () {
        getContent();
    }, 3000);

</script>

</body>
</html>
