<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 11:21
 */


?>
<?php include 'header.php'; ?>

<body>
<div id="root"></div>


<?php include 'menu.php'; ?>
<img src="loading2.gif" alt="" class="loader">

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">

            <hr>
            <h3>Таймлайн eBay
                <small>- показаны самые близкие к окончанию аукциона лоты из группы связанной с нашим названием</small>
            </h3>
            <table id="timelineEbayTable" class="display table table-striped table-hover table-inverse" cellspacing="0"
                   width="100%">
                <!--Таблица конфигурируется в js-->

            </table>
            <hr>
            <!--            <table id="example" class="display table table-striped table-hover table-inverse" cellspacing="0"-->
            <!--                   width="100%">-->
            <!--                <thead>-->
            <!--                <tr>-->
            <!---->
            <!--                                                    <th>id</th>-->
            <!--                    <th>title link(citi)</th>-->
            <!--                    <th>Время</th>-->
            <!--                    <th>citilinkurl</th>-->
            <!--                    <th>synonyms</th>-->
            <!--                    <th>categoryid</th>-->
            <!--                    <th>name</th>-->
            <!--                    <th>citilinkprice</th>-->
            <!--                    <th>picture_url</th>-->
            <!--                    <th>ebay_count</th>-->
            <!--                    <th>minmax_procent</th>-->
            <!--                    <th>ebay_ids</th>-->
            <!--                    <th>citilinkid</th>-->
            <!--                    <th>ebay_price</th>-->
            <!---->
            <!--                </tr>-->
            <!--                </thead>-->
            <!---->
            <!--            </table>-->
            <!--            <hr>-->

            <!-- Button trigger modal -->
            <!--            <div class="d-flex align-items-center justify-content-center subm">-->
            <!--                <div class="d-flex flex-column"> -->
            <form id="formaddCol" action="" data-id="" class="form-inline">
                <label for="addCol">Ссылка на <br>ситилинк &nbsp;</label>
                <input type="text" name="addCol" id="addCol" class="form-control">
                <button type="submit" form="formaddCol" value="Submit" class="btn btn-primary">Искать</button>
                <div class="pl-4">
                <button type="button" form="addManualy" class="btn btn-primary">Добавить позицию вручную (in progress)</button>
                </div>
                <div class="pl-4">
                    <button type="button" form="addFromJson" class="btn btn-primary">Спарсить <small>json список с ситилинка <br> (парсит из файла spisok.json) <br> (Формат: our_name,citilinkURL,synonyms)</small></button>
                </div>
                <div class="pl-4">
                    <button type="button" form="addFromJson" class="btn btn-primary">Запустить автозаполнение eBay</button>
                </div>

            </form>


            <form id="formaddColEdited" action="" data-id="" class="collapse width">
                <div id="resultCity" class="wrapword p-3">

                </div>
                <button type="button" class="btn btn-secondary close closeabs"
                        onclick="$(this).parent().collapse('hide');">&times;
                </button>
            </form>
            <div id="ebayResults" class="collapse width p-3">

                <h5>Результаты поиска</h5>
            </div>
            <!--                </div>-->
            <!--            </div>-->
            <hr>
            <h3>Наша база
                <small>- клик по кнопке в колонке "Результаты выдачи" - запускает запрос списка с eBay</small>
            </h3>
	        <?php
	        echo (__DIR__.'/aj_get_Product_table.php');
	        ?>
            <table id="citiList" class="table table-striped table-hover table-inverse" cellspacing="0" width="100%">
                <thead>
                <tr class="table-primary">
                    <th>наш id</th>
                    <th>Категория</th>
                    <th>Оставшееся время</th>
                    <th>цена на eBay</th>
                    <th>цена ситилинк (USD)</th>
                    <th>Имя в ситилинке</th>
                    <!--                    <th>id ситилинка</th>-->
                    <th>синонимы для поиска</th>
                    <th>Результаты выдачи</th>
                    <th>Min/Max процент (дефолт 50/80)</th>
                    <!--                    <th>ссылка на ситилинк</th>-->
                    <th>Спарсить с ситилинка</th>

                </tr>
                </thead>
                <tbody>

				<?php
				include 'aj_get_Product_table.php';
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formSaveBD" action="" data-id="">
                <h5 class="modal-title">Синонимы</h5>
                <div class="modal-body">
                    ...
                </div>
                <div class="text-right px-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="formSaveBD" value="Submit" class="btn btn-primary">Save changes</button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Редактирование полей</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="formSaveBD" value="Submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalLongAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formSaveBD" action="" data-id="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title">Синонимы</h5>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="formSaveBD" value="Submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="resp">

</div>


<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.js"></script>
<script src="jquery.dataTables.min.js"></script>
<script src="dataTables.bootstrap4.min.js"></script>
<script src="libs2/moment.js"></script>
<script src="libs2/locale/ru.js"></script>
<!--<script src="node_modules/handsontable/dist/handsontable.full.js"></script>-->
<script>
    var gl = {};
    String.format = function () {
        var s = arguments[0];
        for (var i = 0; i < arguments.length - 1; i++) {
            var reg = new RegExp("\\{" + i + "\\}", "gm");
            s = s.replace(reg, arguments[i + 1]);
        }

        return s;
    }
    $(document).ready(function () {

        // $.fn.dataTable.ext.errMode = 'none'; //Выключить алерт ошибок таблиц


        var timelineEbayTable;
        $('#formaddColEdited').collapse('hide'); //Рез-ты по сити
        $('#ebayResults').collapse('hide'); //Рез-ты по ебею
        gl.mainTable = $('#citiList').DataTable({
            "order": [[2, "desc"]],
            paging: false
        });

        var sampleDataFromBackend = {
            id: 162887919038,
            ebay_id: 162887919038,
            product_id: 18,
            datetimeleft: "2018-02-15T13:08:02.000Z",
            title: "Смартфон MEIZU M5s 16Gb, M612H, золотистый",
            min_lefttime: "2018-02-15T13:08:02.000Z",
            citilinkurl: "https://www.citilink.ru/catalog/mobile/cell_phones/495436/",
            synonyms: "MEIZU M5s, 16Gb, M612H, золотистый",
            categoryid: "214",
            citilinkprice: "189",
            picture_url: null,
            last_all_ebay_count: "201",
            last_approve_ebay_count: "3",
            min_procent: null,
            max_procent: null,
            ebay_ids: "292444407279,162887919038,173148389054",
            citilinkid: "495436",
            ebay_price: "129.99",
            url: null,
            pid: "18",
            timestamp: null,
            ebaydata: {
                itemId: "162887919038",
                title: "Corsair Dominator Platinum DDR3 16GB 2400MHz (4x4gb) (CMD16GX3M4A2400C10)",
                globalId: "EBAY-US",
                primaryCategory: {
                    categoryId: "170083",
                    categoryName: "Memory (RAM)"
                },
                galleryURL: "http://thumbs3.ebaystatic.com/m/m9Lv7NU9DB4155F3PSGFuUQ/140.jpg",
                viewItemURL: "http://www.ebay.com/itm/Corsair-Dominator-Platinum-DDR3-16GB-2400MHz-4x4gb-CMD16GX3M4A2400C10-/162887919038",
                paymentMethod: [
                    "PayPal"
                ],
                autoPay: "false",
                postalCode: "92649",
                location: "Huntington Beach,CA,USA",
                country: "US",
                shippingInfo: {
                    shippingServiceCost: {
                        value: "0",
                        currencyId: "USD"
                    },
                    shippingType: "FreePickup",
                    shipToLocations: [
                        "Worldwide"
                    ],
                    expeditedShipping: "false",
                    oneDayShippingAvailable: "false",
                    handlingTime: "3"
                },
                sellingStatus: {
                    currentPrice: {
                        value: "150",
                        currencyId: "USD"
                    },
                    convertedCurrentPrice: {
                        value: "150",
                        currencyId: "USD"
                    },
                    bidCount: "1",
                    sellingState: "Active",
                    timeLeft: "P0DT2H59M10S"
                },
                listingInfo: {
                    bestOfferEnabled: "false",
                    buyItNowAvailable: "false",
                    startTime: "2018-02-05T13:08:02.000Z",
                    endTime: "2018-02-15T13:08:02.000Z",
                    listingType: "Auction",
                    gift: "false"
                },
                returnsAccepted: "false",
                condition: {
                    conditionId: "3000",
                    conditionDisplayName: "Used"
                },
                isMultiVariationListing: "false",
                topRatedListing: "false"
            }
        };
        timelineEbayTable = $('#timelineEbayTable').DataTable({
            "paging": false,
            "ordering": false,
            "ajax": {
                url: "aj_Get_BD_ebay.php",
                dataSrc: ''
            },
            "columns": [
                {
                    "width": "2%",
                    "title": "наш id",
                    "data": "product_id",
                },
                {
                    "width": "2%",
                    "title": "Время окончания",
                    "data": "datetimeleft",
                    "render": function (data, type, row, meta) {
                        return moment(data, moment.ISO_8601, 'ru').format("MMM DD HH:mm");
                    }
                },
                {
                    "title": "Категория ebay",
                    "data": "ebaydata.primaryCategory.categoryName"
                },
                {
                    "title": "Цена отсчета (ситилинк)",
                    "data": "citilinkprice"
                },
                {
                    "title": "Цена ebay",
                    "data": "ebaydata.sellingStatus.currentPrice.value"
                },
                {
                    "title": "Картинка с ebay",
                    "data": "ebaydata",
                    render: function (data, type, row, meta) {
                        return '<img src="' + data.galleryURL + '" height="70">';
                    }
                },
                {
                    "title": "Название и ссылка(…) ebay",
                    "data": "ebaydata",
                    render: function (data, type, row, meta) {
                        return `${data.title} <a href="${data.viewItemURL}" target="_blank">…</a>`;
                    }
                },
                {
                    "title": "Наше название",
                    "data": "our_name"
                },
                {
                    "title": "Название ситилинка",
                    "data": "citilink_data.productName"
                },
                {
                    "title": "Синонимы",
                    "data": "synonyms"
                },


                {
                    "title": "Результаты выдачи",
                    "data": null,
                    render: function (data, type, row, meta) {
                        return '<button class="runIt btn btn-outline-info btn-sm">' + data.last_approve_ebay_count + ' / ' + data.last_all_ebay_count + '</button>';
                    }
                },
                {
                    "title": "Минимальный и максимальный % (по дефолту 50/80)",
                    "data": null,
                    render: function (data, type, row, meta) {
                        return (data.min_procent ? '<span class="badge badge-primary">' + data.min_procent + '</span> / ' : '') + (data.max_procent ? '<span class="badge badge-primary">' + data.max_procent+ '</span>' : '');
                    }
                }, {
                    "title": "(chexbox = автоматом) Отправить в торги",
                    "data": null,
                    render: function (data, type, row, meta) {
                        return '<input type="checkbox" class="form-check-input"><button type="button" class="btn btn-primary btn-sm">&rarr; (in progress)</button>';
                    }
                },
            ]
        });
        setInterval(function () {
            console.log('timelineEbayTable redraw');
            timelineEbayTable.ajax.reload();
        }, 5000);

        $('#timelineEbayTable tbody').on('click','.runIt',function (e) {
            var ourID = $(e.target).closest('tr').find('td').eq(0).text(); //в первой колонке должен быть наш id
            console.log(ourID);


        })

    });

    // setInterval(function () {
    //     refreshMainTable();
    // },3000);

    function refreshMainTable() {
        // options = tableVar.settings();
        gl.mainTable.destroy();
        $.ajax({
            url: "aj_get_Product_table.php",
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();
                $('#citiList tbody').empty().html(data);
                // setTimeout(function () {
                gl.mainTable = $('#citiList').DataTable({
                    "order": [[2, "desc"]],
                    paging: false
                });
                // },1000)
            }
        });

    }
    function get_eBay() {
        
    }
    
    $('body').on('click', 'tr[id^="cid"]', function (e) {
        //Если не нажата кнопка на результах выдачи или Поиска по ситилинку
        if (!($(e.target).hasClass('runIt') || $(e.target).hasClass('runCiti'))) {
            $('button.runModal').attr('data-cid', $(this).attr('data-cid'));
            $('#exampleModalLong').modal();
            // event.preventDefault();
            $('#resp').html(JSON.stringify($(this).data("all")));
            $.ajax({
                type: "POST",
                data: JSON.stringify($(this).data("all")),
                // contentType: 'application/json',
                // dataType: 'json',
                url: "aj_Get_BD.php",
                success: function (data) {
                    var resp = JSON.parse(data);
                    // console.log(resp);
                    $('#exampleModalLongTitle').html(resp.citilinkid);
                    $('.modal-body,.modal-header').html('');
                    $.each(resp, function (k, v) {
                        if (k != 'synonyms') $('.modal-header').append(
                            '<div class="input-group ' + k + '">' +
                            '<span class="input-group-addon ">' + k + '</span>' +
                            '<input type="text" class="form-control" name="' + k + '"value="' + v + '">' +
                            '</div>');
                    })
                    // var syn = resp.synonyms ? resp.synonyms.split(';') : null;
                    $('.modal-body').append('<textarea rows="3" class="form-control" name="' + 'synonyms' + '"value="' + resp.synonyms + '">' + resp.synonyms + '</textarea>');
                }
            })
        } else if ($(e.target).hasClass('runIt')) {
            //окно и аякс на Поиск по eBay
            var thatID = $(this).attr('data-id');
            console.log(thatID);

            $.ajax({
                type: "POST",
                // data     : {data:values},
                data: $(this).data().all,
                url: "aj_Get_ebay.php",
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (data) {
                    $('.loader').hide();
                    $('#ebayResults').collapse('show').html(data);
                    $('#tableEbayResults').attr('data-id', thatID);
                    gl.etable = $('#tableEbayResults').DataTable({
                        "order": [[1, "asc"]]
                    });
                }
            });
        } else if ($(e.target).hasClass('runCiti')) {
            //Аякс на конечный поиск "клик по стрелочке"
            console.log('runCiti');
        }

    });

    $('#exampleModalLong').on('shown.bs.modal', function () {
        $('[name="synonyms"]').focus()
    });

    $('#formaddCol').on('submit', function (e) {
        //кнопка ИСКАТЬ по линку ситили
        e.preventDefault();
        $('#resultCity').html('');
        $('#formaddColEdited').collapse('show');//.css({display:'flex'});
        // $('<div class="cclose">').text('X').css({position:'absolute',right:'0',top:'0'});
        // $('[name="synonyms"]').focus()
        console.log('input val', $(this).find('input#addCol').val());
        $.ajax({
            type: "POST",
            // data     : {data:values},
            data: {'target': $(this).find('input#addCol').val()},
            url: "aj_find_citilink_byCurl.php",
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                var resp = JSON.parse(data);
                gl.resp = resp;
                console.log(gl.resp);
                // $('#resultCity').html(resp.productName);
                $('.loader').hide();
                $('#resultCity').html(
                    '<h5>Данные по запросу от ситилинка:</h5>' +
                    '<img src="' + gl.resp.productPictureUrl + '" width="100">' +
                    '<p><b>Название ситилинка </b><input class="form-control" type="text" value="' + gl.resp.productName + '"></p>' +
                    '<p><b>Наше название  </b><input id="ourName" class="form-control" type="text" value="' + gl.resp.productName + '"></p>' +
                    '<p><b>Цена: </b>' + gl.resp.productPrice + ' руб. В долларах: <span id="priceFromCity">' + (gl.resp.productPrice / <?php echo $dollar;?>).toFixed() + '</span> USD</p>' +
                    '<p><b>id продукта: </b>' + gl.resp.productId + '</p>' +
                    '<p><b>Категория: </b>' + gl.resp.categoryName + ' (id категории : ' + gl.resp.categoryId + ')</p>' +
                    '<p><b>fullRealCategoryName: </b>' + gl.resp.fullRealCategoryName + '</p>' +
                    '<p><b>Синонимы: </b>' + '<textarea rows="3" class="form-control" name="' + 'synonyms' + '"value="' + gl.resp.productName + '">' + gl.resp.productName + '</textarea></p>' +
                    '<button type="submit" form="formaddColEdited" value="Submit" class="btn btn-primary">Сохранить в БД (без eBay)</button>' +
                    '<button id="preSearchEbay" type="button" class="btn btn-primary">Искать по ebay</button>' +
                    // '<p><b>Название</b>' + resp.productName + '</p>' +
                    // '<p><b>Название</b>' + resp.productName + '</p>' +
                    ''
                );
                gl.senddataCiti = {
                    'title': gl.resp.productName,
                    'citilinkurl': gl.resp.productUrl,
                    'our_name': $('#formaddColEdited').find('#ourName').val(),
                    'citilinkid': gl.resp.productId,
                    'citilinkprice': (gl.resp.productPrice / <?php echo $dollar;?>).toFixed(),
                    'categoryid': gl.resp.categoryId,
                    'picture_url': gl.resp.productPictureUrl,
                    'synonyms': $('#formaddColEdited').find('textarea[name="synonyms"]').val(),
                    'citilink_data': gl.resp
                };
                // console.log(gl.senddataCiti.synonyms);

            }
        });
    });

    //Обрабатываем изменение ввода синониммов, чтоб занести в глобал
    $('#formaddColEdited').on('change', '[name=synonyms]', function (e) {
        gl.senddataCiti.synonyms = $(this).val();
        console.log(gl.senddataCiti['synonyms']);
    });

    //Предварительный поиск в ebay из формы поиска по ссылке ситилинка
    $('body').on('click', '#preSearchEbay', function (e) {
        var formFromCity = $(this).closest('form');
        var synonymsFromCity = formFromCity.find('[name="synonyms"]').val();
        var priceFromCity = formFromCity.find('#priceFromCity').eq(0).text();
        console.log(priceFromCity);
        $.ajax({
            type: "POST",
            // data     : {data:values},
            data: {
                'synonyms': synonymsFromCity,
                'citilinkprice': priceFromCity
            },
            url: "aj_Get_ebay.php",
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();
                $('#ebayResults').collapse('show').html(data);
                gl.etable = $('#tableEbayResults').DataTable({
                    "order": [[1, "asc"]]
                });
            }
        });
    });

    $('body').on('click', '#ebaySubmit', function (e) {
        // [BUTTON] Добавить отмеченные в БД
        e.preventDefault();
        gl.ebay = [];
        gl.ebayIDs = [];
        // Вычленяем адишники ебея по отмеченным чекбоксам. Общее кол-во найденных - с пхп приезжает в gl.eresp //TODO Убреть бы это из пхп
        $("input:checked", gl.etable.rows().nodes()).each(function () {
            console.log($.parseJSON($(this).closest('tr').attr('data')));
            // var e = $(this).closest('tr').attr('id');
            // var eid = e.substring(7, e.length);
            // var etimeleft = $(this).closest('tr').find('td').eq(1).text();
            // var eprice = $(this).closest('tr').find('td').eq(-1).text();
            e = $.parseJSON($(this).closest('tr').attr('data'));
            gl.ebay.push(
                e
                // 'id': e.itemId,
                // 'timeleft': e.listingInfo.endTime,
                // 'price': e.sellingStatus.currentPrice.value
            );
            console.log(gl.ebay);
            gl.ebayIDs.push(e.itemId);
        });
        gl.ebay.sort(function (a, b) {
            // debugger;
            // if (a.timeleft > b.timeleft) {
            if (a.listingInfo.endTime > b.listingInfo.endTime) {
                return 1;
            }
            // if (a.timeleft < b.timeleft) {
            if (a.listingInfo.endTime < b.listingInfo.endTime) {
                return -1;
            }
            return 0; //a = b
        });
        var curID = ($('#tableEbayResults').attr('data-id'));
        console.log('gl.ebay');
        console.log(gl.ebay);
        console.log(gl.eresp);
        var predata = {
            'id': curID,
            'min_lefttime': gl.ebay[0].listingInfo.endTime,
            'ebay_price': e.sellingStatus.currentPrice.value,
            'ebay_ids': gl.ebayIDs.join(),
            'last_all_ebay_count': gl.eresp,
            'last_approve_ebay_count': gl.ebay.length,
            'ebaydata': gl.ebay,
        };
        var senddata = Object.assign({}, gl.senddataCiti, predata);
        console.log(senddata);
        $.ajax({
            type: "POST",
            data: {data: senddata},
            url: "aj_Set_BD.php",
            success: function (data) {
                $('#formTableEbayResults').append('<p>' + data + '</p>');
                console.log(data);
                refreshMainTable();

                // gl.mainTable.ajax.reload();
            },
        });

    });

    // Сохраняем в БД из поиска ситилинка, без предварительных eBay ласк.
    $('#formaddColEdited').on('submit', function (e) {
        e.preventDefault();
        console.log(gl.senddataCiti);
        $.ajax({
            type: "POST",
            // dataType: "json",
            data: {data: gl.senddataCiti},
            url: "aj_Set_BD.php",

            success: function (data) {
                // $('#resp').html(data);
                console.log(data);
                $('#resultCity').append('<p>' + data + '</p>');
                // gl.mainTable.ajax.reload();
            },
            error: function (jqXHR, exception) {
                $('#resp').show();

                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('#resp').html(msg);
            },
        });
    });

    //сохраняем поля в БД из модалки
    $('#formSaveBD').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            // data     : {data:values},
            data: $(this).serialize(),
            url: "aj_Set_BD.php",

            success: function (data) {
                $('#resp').html(data);
                $('#exampleModalLong').modal('hide');
                // gl.mainTable.ajax.reload();
            }
        });
    })
    // console.log($(this).attr('data-cid'));
</script>

<!--REACT will be here-->
<!--<script src="libs2/react.development.js"></script>-->
<!--<script src="libs2/react-dom.development.js"></script>-->
<!--<script src="libs2/babel.min.js"></script>-->
<!--<script type="text/babel">-->
<!---->
<!--    ReactDOM.render(-->
<!--        <div className="text-right">-->
<!--            <h6>React here</h6>-->
<!--        </div>-->
<!--        , document.getElementById('root')-->
<!--    );-->
<!---->
<!--</script>-->
</body>
</html>
