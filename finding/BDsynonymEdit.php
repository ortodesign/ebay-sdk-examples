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
<?php include 'menu.php'; ?>
<img src="loading2.gif" alt="" class="loader">

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">
                        <hr>
                        <table id="example"   class="display table table-striped table-hover table-inverse" cellspacing="0" width="100%">
                            <thead>
                            <tr>

                                <th>id</th>
                                <th>title link(citi)</th>
                                <th>Время</th>
                                <th>citilinkurl</th>
                                <th>synonyms</th>
                                <th>categoryid</th>
                                <th>name</th>
                                <th>citilinkprice</th>
                                <th>picture_url</th>
                                <th>ebay_count</th>
                                <th>minmax_procent</th>
                                <th>ebay_ids</th>
                                <th>citilinkid</th>
                                <th>ebay_price</th>

                            </tr>
                            </thead>

                        </table>
                        <hr>
            <!-- Button trigger modal -->
            <!--            <div class="d-flex align-items-center justify-content-center subm">-->
            <!--                <div class="d-flex flex-column">-->
            <form id="formaddCol" action="" data-id="" class="form-inline">
                <label for="addCol">Ссылка на <br>ситилинк &nbsp;</label>
                <input type="text" name="addCol" id="addCol" class="form-control">
                <button type="submit" form="formaddCol" value="Submit" class="btn btn-primary">Искать</button>
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

				<?php require_once 'aj_get_Product_table.php' ?>

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
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="node_modules/handsontable/dist/handsontable.full.js"></script>
<script>
    var gl = {};
    $(document).ready(function () {
        $('#formaddColEdited').collapse('hide'); //Рез-ты по сити
        $('#ebayResults').collapse('hide'); //Рез-ты по ебею
        gl.mainTable = $('#citiList').DataTable({
            "order": [[2, "desc"]],
            paging: false
        });

        //Оставить пока как ексемпл да json
        var exampleTable = $('#example').DataTable( {
            "paging": false,
            "ajax": {
                url: "aj_get_Mix_Product_json.php",
                dataSrc: ''
            },
            "columns": [
                { "data": "id" },
                { "data": "link" },
                // { "data": "title" },
                { "data": "min_lefttime" },
                // { "data": "citilinkurl" },
                { "data": "synonyms" },
                { "data": "categoryid" },
                { "data": "citilinkprice" },
                { "data": "picture_url" },
                { "data": "ebay_ids" },
                { "data": "citilinkid" },
                { "data": "ebay_price" },
                { "data": "name" },
                { "data": "ebay_count" },
                { "data": "minmax_procent" },
                { "data": "datetimeleft" },

                    //     id: 1,
                        // title: "Смартфон SAMSUNG Galaxy S7 32Gb, SM-G930FD, черный",
                        // min_lefttime: "2018-02-08T17:26:04+0000",
                        // citilinkurl: "https://www.citilink.ru/catalog/mobile/cell_phones/357582/",
                        // synonyms: "SM-G930FD,G930FD,SAMSUNG Galaxy S7 ",
                        // categoryid: 214,
                        // citilinkprice: 531.843,
                        // picture_url: "null",
                        // ebay_ids: "132490654126,282836792358,122945859123",
                        // citilinkid: 357582,
                        // ebay_price: "405.00",
                        // name: "Мобильные телефоны",
                        // ebay_count: "<button class="runIt">3 / 7</button>",
                        // minmax_procent: "0 / 0"

            ]
        } );


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

    $('body').on('click', 'tr[id^="cid"]',function (e) {
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
                    '<p><b>Название </b><input class="form-control" type="text" value="' + gl.resp.productName + '"></p>' +
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
                    'citilinkid': gl.resp.productId,
                    'citilinkprice': (gl.resp.productPrice / <?php echo $dollar;?>).toFixed(),
                    'categoryid': gl.resp.categoryId,
                    'picture_url': gl.resp.productPictureUrl,
                    'synonyms': $('#formaddColEdited').find('textarea[name="synonyms"]').val()
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
            var e = $(this).closest('tr').attr('id');
            var eid = e.substring(7, e.length);
            var etimeleft = $(this).closest('tr').find('td').eq(1).text();
            var eprice = $(this).closest('tr').find('td').eq(-1).text();
            gl.ebay.push({
                'id': eid,
                'timeleft': etimeleft,
                'price': eprice
            });
            gl.ebayIDs.push(eid);
        });
        gl.ebay.sort(function (a, b) {
            if (a.timeleft > b.timeleft) {
                return 1;
            }
            if (a.timeleft < b.timeleft) {
                return -1;
            }
            return 0; //a = b
        });
        var curID = ($('#tableEbayResults').attr('data-id'));
        console.log(gl.eresp);
        var predata = {
            'id': curID,
            'min_lefttime': gl.ebay[0].timeleft,
            'ebay_price': gl.ebay[0].price,
            'ebay_ids': gl.ebayIDs.join(),
            'last_all_ebay_count': gl.eresp,
            'last_approve_ebay_count': gl.ebayIDs.length,
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
</body>
</html>
