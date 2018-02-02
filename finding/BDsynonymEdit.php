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


?>
<?php include 'header.php'; ?>

<body>
<?php include 'menu.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <!-- Button trigger modal -->
            <div class="d-flex align-items-center justify-content-center subm">
                <div class="d-flex flex-column">
                    <form id="formaddCol" action="" data-id="">
                        <input type="text" name="addCol" id="addCol">
<!--                        <button type="button" class="runModalAdd btn btn-primary" data-toggle="modal"-->
<!--                                data-target="#exampleModalLongAdd"-->
<!--                                data-cid="">-->
<!--                            ДОБАВИТЬ-->
<!--                        </button>-->
                        <button type="submit" form="formaddCol" value="Submit" class="btn btn-primary">ДОБАВИТЬ</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <hr>

            <table class="table table-striped table-dark table-hover table-inverse" id="citiList">
                <!--                <thead>-->
                <tr class="table-primary">
                    <th>наш id</th>
                    <th>Имя в ситилинке</th>
                    <th>цена на ситилинке (USD)</th>
                    <th>id ситилинка</th>
                    <th>синонимы для поиска</th>
                    <th>ссылка на ситилинк</th>
                    <th>RUN</th>
                    <th></th>
                </tr>
				<?php
				$product = new  Product;
				//				foreach ( $product->all() as $k => $v ) {
				foreach ( $product::find( 'all', array( 'order' => 'id desc' ) ) as $k => $v ) {
					echo '<tr id="cid' . $v->citilinkid . '" data-cid="' . $v->citilinkid . '" data-all="' . htmlspecialchars( json_encode( $v->attributes() ) ) . '">';
					print_r( '<td>' . $v->id . '</td>' );
					print_r( '<td>' . $v->title . '</td>' );
					print_r( '<td>' . $v->citilinkprice . '</td>' );
					print_r( '<td>' . $v->citilinkid . '</td>' );
					print_r( '<td class="synonyms">' . $v->synonyms . '</td>' );
					print_r( '<td class="wrapword">' . $v->citilinkurl . '</td>' );
//					print_r( '<td>' . '<button>&times;</button>' . '</td>' );
					print_r( '<td>' . '<button class="runIt">&rarr;</button>' . '</td>' );
					echo '<tr>';
				}


				?>
                <!--                </tbody>-->
            </table>
            <!-- Button trigger modal -->
            <div class="d-flex align-items-center justify-content-center subm">
                <div class="d-flex flex-column">
                    <button type="button" class="runModal btn btn-primary" data-toggle="modal"
                            data-target="#exampleModalLong"
                            data-cid="">
                        Launch modal
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
<div id="ebayResults">
    <h5>Результаты поиска</h5>
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

    .subm {
        min-height: 100px;
    }

    .wrapword {
        word-wrap: break-word;
        word-break: break-all;
        white-space: pre-wrap;
    }

    #resp {
        max-width: 18%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        text-wrap: normal;
        min-height: 100px;
        position: fixed;
        left: 20px;
        bottom: 50px;
        background: rgba(100, 100, 100, .2);
    }

    #ebayResults {
        max-width: 18%;
        width: 18%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        overflow-y: scroll;
        text-wrap: normal;
        min-height: 85%;
        position: absolute;
        right: 20px;
        top: 60px;
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

<!--<script type="text/javascript" src="jQuery-3.2.1/jquery-3.2.1.js"></script>-->
<!--<script type="text/javascript" src="DataTables-1.10.16/js/jquery.dataTables.js"></script>-->
<!--<script type="text/javascript" src="DataTables-1.10.16/js/dataTables.bootstrap4.js"></script>-->
<script>
    // $(document).ready(function(){
    //     $('#citiList').DataTable();
    // });


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
            ;
        } else {
            //Аякс на конечный поиск
            // console.log('search in Ebay');
            // console.log($(this).data().all.synonyms);
            $.ajax({
                type: "POST",
                // data     : {data:values},
                data: $(this).data().all,
                url: "aj_Get_ebay.php",
                success: function (data) {
                    $('#ebayResults').html(data);
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
            data: {'target' : $(this).find('input#addCol').val()},
            url: "aj_find_citilink_byCurl.php",
            success: function (data) {
                $('#ebayResults').html(data);
            }
        });
        //не парси с БД - руками забей тут поля и сериализуй на aj_Set_BD.php быстрее будет (или парсить?)


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
