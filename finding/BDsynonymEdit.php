<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 11:21
 */
require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
require_once 'functions.php';
//require_once   __DIR__ . '/../models/Product.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);
$dollar      = getDollarCourse();
// initialize ActiveRecord
ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
//	$cfg->set_model_directory( __DIR__ . '/../models');
	$cfg->set_connections( $connections );
} );

class Product extends ActiveRecord\Model {
	static $table_name = 'Product';
	static $connection = 'production';
}

//
//class Keywords extends ActiveRecord\Model {
//	static $table_name = 'keywords';
//	static $connection = 'production';
//}

class Category extends ActiveRecord\Model {
	static $table_name = 'category';
	static $connection = 'production';
}


?>
<?php include 'header.php'; ?>

<body>
<?php include 'menu.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-5">
            <hr>
            <!-- Button trigger modal -->
            <!--            <div class="d-flex align-items-center justify-content-center subm">-->
            <!--                <div class="d-flex flex-column">-->
            <form id="formaddCol" action="" data-id="" class="form-inline">
                <label for="addCol">Ссылка на <br>ситилинк &nbsp;</label>
                <input type="text" name="addCol" id="addCol" class="form-control">

                <button type="submit" form="formaddCol" value="Submit" class="btn btn-primary">Искать</button>
            </form>
            <form id="formaddColEdited" action="" data-id="" class="">
                <img src="loading2.gif" alt="" class="loader">
                <div id="resultCity" class="wrapword">

                </div>
            </form>
            <div id="ebayResults">
                <h5>Результаты поиска</h5>
                <img src="loading2.gif" alt="" class="loader">
            </div>
            <!--                </div>-->
            <!--            </div>-->

        </div>
        <div class="col-sm-7">
            <hr>

            <table id="citiList" class="table table-striped table-hover table-inverse" cellspacing="0" width="100%">
                <!--            <table id="citiList" class="table table-striped table-bordered" cellspacing="0" width="100%">-->
                <!--            <table class="table table-striped table-dark table-hover table-inverse" id="citiList">-->
                <thead>
                <tr class="table-primary">
                    <th>наш id</th>
                    <th>Категория</th>
                    <th>Имя в ситилинке</th>
                    <th>цена на ситилинке (USD)</th>
                    <th>id ситилинка</th>
                    <th>синонимы для поиска</th>
                    <th>Результаты выдачи</th>
                    <th>Min/Max процент (дефолт 50/80)</th>
                    <!--                    <th>ссылка на ситилинк</th>-->
                    <th>RUN</th>

                </tr>
                </thead>
                <tbody>
				<?php
				$product  = new  Product;
				$category = new  Category;

				//				foreach ( $product->all() as $k => $v ) {
				foreach ( $product::find( 'all', array( 'order' => 'id desc' ) ) as $k => $v ) {
					echo '<tr id="cid' . $v->citilinkid . '" data-id="' . $v->id . '" data-cid="' . $v->citilinkid . '" data-all="' . htmlspecialchars( json_encode( $v->attributes() ) ) . '">';
					print_r( '<td>' . $v->id . '</td>' );
					print_r( '<td>' . $category::all( array(
							'conditions' => array( 'citi_category_id = ?', $v->categoryid )
						) )[0]->name . '</td>' );
//					print_r( '<td>' . $category::find( $v->categoryid )[0]->name . '</td>' );
					print_r( '<td><a target="_blank" href="' . $v->citilinkurl . '">' . $v->title . '</a></td>' );
					print_r( '<td>' . $v->citilinkprice . '</td>' );
					print_r( '<td>' . $v->citilinkid . '</td>' );
					print_r( '<td class="synonyms">' . $v->synonyms . '</td>' );
					print_r( '<td>' . $v->last_approve_ebay_count . ' / ' . $v->last_all_ebay_count . '</td>' );
					print_r( '<td>' . $v->min_procent . ' / ' . $v->max_procent . '</td>' );
//					print_r( '<td class="wrapword">' . $v->citilinkurl . '</td>' );
//					print_r( '<td>' . '<button>&times;</button>' . '</td>' );
					print_r( '<td>' . '<button class="runIt">&rarr;</button>' . '</td>' );
					echo '</tr>';
				}


				?>
                </tbody>
            </table>
            <!-- Button trigger modal -->
<!--            <div class="d-flex align-items-center justify-content-center subm">-->
<!--                <div class="d-flex flex-column">-->
<!--                    <button type="button" class="runModal btn btn-primary" data-toggle="modal"-->
<!--                            data-target="#exampleModalLong"-->
<!--                            data-cid="">-->
<!--                        Launch modal-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <!--        <div class="col-sm-2">-->
        <!---->
        <!--        </div>-->

    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
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

<style>
    table {
        font-size: .7em;
    }

    .loader {
        display: none;
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        margin: auto;
        max-width: 200px;
        z-index: 99;
    }

    .subm {
        /*min-height: 100px;*/
    }

    .wrapword {
        word-wrap: break-word;
        word-break: break-all;
        white-space: pre-wrap;
    }

    #resp {
        display: none;
        max-width: 18%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        text-wrap: normal;
        min-height: 100px;
        position: fixed;
        left: 20px;
        bottom: 50px;
        border: 2px solid red;
        background: rgba(255, 255, 255, .2);
    }

    #ebayResults {
        max-width: 95%;
        width: 95%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        overflow-y: scroll;
        text-wrap: normal;
        /*min-height: 85%;*/
        position: static;
        /*right: 20px;*/
        /*top: 60px;*/
        background: rgba(100, 100, 100, .2);
        /*z-index: 1;*/
    }

    #resultCity {
        max-width: 95%;
        width: 95%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        overflow-y: scroll;
        text-wrap: normal;
        min-height: 85%;
        /*position: absolute;*/
        /*left: 20px;*/
        /*top: 80px;*/
        background: rgba(100, 100, 100, .2);
        z-index: 1;
    }

    .modal-dialog {
        max-width: 90%;
    }

    .modal .input-group {
        width: 100%;
    }

    .modal .modal-header {
        flex-wrap: wrap;
    }
</style>
<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script>
    var gl = {};
    $(document).ready(function () {
        $('#citiList').DataTable({
            "order": [[0, "desc"]]
        });
    });

    $('tr[id^="cid"]').on('click', function (e) {
        console.log($(e.target)[0].tagName);
        if ($(e.target)[0].tagName != 'BUTTON') {
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
        } else {
            //Аякс на конечный поиск "клик по стрелочке"
            // console.log('search in Ebay');
            // console.log($(this).data().all.synonyms);
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
                    $('#ebayResults').html(data);
                    $('#tableEbayResults').attr('data-id', thatID);
                    gl.etable = $('#tableEbayResults').DataTable({
                        "order": [[5, "asc"]]
                    });
                }
            });


        }
    })
    ;
    $('#exampleModalLong').on('shown.bs.modal', function () {
        $('[name="synonyms"]').focus()
    })
    $('#formaddCol').on('submit', function (e) {
        e.preventDefault();

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
                    '<button type="submit" form="formaddColEdited" value="Submit" class="btn btn-primary">Сохранить в БД</button>' +
                    '<button id="preSearchEbay" type="button" class="btn btn-primary">Искать по ebay</button>' +
                    // '<p><b>Название</b>' + resp.productName + '</p>' +
                    // '<p><b>Название</b>' + resp.productName + '</p>' +
                    ''
                );
            }
        });
        //не парси с БД - руками забей тут поля и сериализуй на aj_Set_BD.php быстрее будет (или парсить?)


    });
    $('body').on('click', '#preSearchEbay', function (e) {
        console.log('Предварительный поиск в ebay из формы поиска по ссылке ситилинка');
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
                $('#ebayResults').html(data);
                gl.etable = $('#tableEbayResults').DataTable({
                    "order": [[5, "asc"]]
                });
            }
        });
    });

    $('body').on('click', '#ebaySubmit', function (e) {
        // Вычленяем адишники ебея по отмеченным чекбоксам. Общее кол-во найденных - костылем с пхп приезжает в gl.eresp
        e.preventDefault();
        gl.ebayIDs = [];
        $("input:checked", gl.etable.rows().nodes()).each(function () {
            var e = $(this).closest('tr').attr('id');
            var eid = e.substring(7, e.length);
            gl.ebayIDs.push(eid);
        });
        console.log(gl.ebayIDs);
        var curID = ($('#tableEbayResults').attr('data-id'));
        console.log(gl.eresp);

        var senddata = {
            'id': curID,
            'ebay_ids': gl.ebayIDs.join(),
            'last_all_ebay_count': gl.eresp,
            'last_approve_ebay_count': gl.ebayIDs.length,
        };
        console.log(senddata);
        $.ajax({
            type: "POST",
            data: {data: senddata},
            url: "aj_Set_BD.php",
            success: function (data) {
                console.log(data);
            },
        });

    });


    $('#formaddColEdited').on('submit', function (e) {
        e.preventDefault();

        var senddata = {
            'title': gl.resp.productName,
            'citilinkurl': gl.resp.productUrl,
            'citilinkid': gl.resp.productId,
            'citilinkprice': (gl.resp.productPrice / <?php echo $dollar;?>).toFixed(),
            'categoryid': gl.resp.categoryId,
            'picture_url': gl.resp.productPictureUrl,
            'synonyms': $(this).find('textarea[name="synonyms"]').val()
        };
        console.log(senddata);
        $.ajax({
            type: "POST",
            // dataType: "json",
            data: {data: senddata},
            url: "aj_Set_BD.php",

            success: function (data) {
                // $('#resp').html(data);
                console.log(data);
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
    $('#formSaveBD').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            // data     : {data:values},
            data: $(this).serialize(),
            url: "aj_Set_BD.php",

            success: function (data) {
                $('#resp').html(data);
                $('#exampleModalLong').modal('hide')
            }
        });
    })
    // console.log($(this).attr('data-cid'));
</script>
</body>
</html>
