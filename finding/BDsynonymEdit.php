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
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap.min.css">
    <title>Редактируем синонимы</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-dark table-hover">
                <tr class="table-primary">
                    <th scope="col">наш id</th>
                    <th scope="col">Имя в ситилинке</th>
                    <th scope="col">id ситилинка</th>
                    <th scope="col">ссылка на ситилинк</th>
                    <th scope="col"></th>
                </tr>
				<?php

				$product = new  Product;
				//if ( isset( $_POST['citilink'] ) ) {
				//	$citilink = getCitilinkJsonFromPyServ( $_POST['name'] );
				$citilink = getCitilinkJsonFromFile( 'sumsung_galaxy_s7.json' );

				//	require __DIR__ . '/../models/Product.php';
				//	var_dump($citilink);
				foreach ( $product->all() as $k => $v ) {

					echo '<tr id="cid' . $v->citilinkid . '" data-cid="' . $v->citilinkid . '" data-all="' . htmlspecialchars( json_encode( $v->attributes() ) ) . '">';
//					echo '<pre>';
////					echo object_to_array($v);
//
//
//					print_r( $v);
//					echo json_encode($v->attributes());
//
//					echo '</pre>';
					print_r( '<td>' . $v->id . '</td>' );
					print_r( '<td>' . $v->title . '</td>' );
					print_r( '<td>' . $v->citilinkid . '</td>' );
					print_r( '<td>' . $v->citilinkurl . '</td>' );
					print_r( '<td>' . '<button href="#">&times;</button>' . '</td>' );
//		if (! $product::all( array( 'conditions' => array( 'citilinkID = ?', $v->id ) ) ) ) { // если айди нет в базе
//			$product::create( array(
//				'title'       => $v->shortName,
//				'citilinkURL' => $v->url,
//				'citilinkID'  => $v->id,
//				'keywordID'   => 1
//			) );
//		}
//		else {
//			$product::find('all', array( 'conditions' => array( 'citilinkID = ?', $v->id ) ) )->update_attributes(array(
//				'title'       => $v->shortName,
//				'citilinkURL' => $v->url,
//				'citilinkID'  => $v->id,
//				'keywordID'   => 1
//			) );
//		}
//		$product->save();
					echo '<tr>';

				}

				//	foreach ( $product::all() as $k => $v ) {
				////                        print_r($k.'<br>');
				//		print_r( $v->id . ' : ' . $v->title . '<br>' );
				//	}
				//}
				print( '</pre><br>' );
				//echo '<h5>Соответствие категорий:</h5><pre>';
				//var_dump( getCategoriesFromJsonFile() );
				//print( '</pre><br>' );

				echo '<br>';

				?>

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
                    <button type="submit" form="formSaveBD" value="Submit"  class="btn btn-primary">Save changes</button>
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

    #resp {
        max-width: 18%;
        /*text-overflow: ellipsis;*/
        overflow-x: scroll;
        text-wrap: normal;
        min-height: 100px;
        position: fixed;
        left: 20px;
        top: 50px;
        background: rgba(100, 100, 100, .2);
    }

    .modal-dialog {
        max-width: 90%;
    }

    #exampleModalLongTitle .input-group {
        width: 100%;
    }

    #exampleModalLong .modal-header {
        flex-wrap: wrap;
    }
</style>
<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.js"></script>
<script>
    $('tr[id^="cid"]').on('click', function () {
        // $('.modal-body').html('<H1>' + $(this).attr('data-cid') + '</H1>');
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
                // console.log(data);
                // console.log(JSON.parse(data));
                var resp = JSON.parse(data);
                console.log(resp);
                $('#exampleModalLongTitle').html(resp.citilinkid);
                $('.modal-body,.modal-header').html('');
                $.each(resp, function (k, v) {

                    // if (k === 'citilinkurl') {
                    //     $('#exampleModalLongTitle').append('<b>' + k + '</b> : <a href="' + v + '" target="_blank"> link </a><br>');
                    // } else
                    if (k != 'synonyms') $('.modal-header').append(
                        '<div class="input-group ' + k + '">' +
                        '<span class="input-group-addon ">' + k + '</span>' +
                        '<input type="text" class="form-control" name="' + k + '"value="' + v + '">' +
                        '</div>');


                })
                var syn = resp.synonyms ? resp.synonyms.split(';') : null;


                // $.each(syn, function () {
                //     // console.log(this);
                //     $('.modal-body').append('<b>' + this + '</b><br>');
                // });
                // if (syn) {
                $('.modal-body').append('<textarea rows="3" class="form-control" name="' + 'synonyms' + '"value="' + resp.synonyms + '">' + resp.synonyms + '</textarea>');
                // }

                // $('.modal-body').html(resp);

                // $.each(resp,function (k,v) {
                //     $('.modal-body').append(k+' : '+v+'<br>');
                // });

            }
        })
        ;
    })
    ;
    $('#exampleModalLong').on('shown.bs.modal', function () {
        $('textarea[name="synonyms"]').focus()
    })
    $('#formSaveBD').on('submit',function (e) {
        e.preventDefault();
        console.log(e);
        console.log($(this).serialize());
        $.ajax({
            type: "POST",
            // data     : {data:values},
            data: $(this).serialize(),
            url: "aj_Set_BD.php",
            success: function (data) {
                $('#resp').html(data);
            }
        });
    })
    // console.log($(this).attr('data-cid'));
</script>
</body>
</html>
