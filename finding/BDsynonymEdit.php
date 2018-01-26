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
                </tr>
				<?php

				$product = new  Product;
				//if ( isset( $_POST['citilink'] ) ) {
				//	$citilink = getCitilinkJsonFromPyServ( $_POST['name'] );
				$citilink = getCitilinkJsonFromFile( 'sumsung_galaxy_s7.json' );

				//	require __DIR__ . '/../models/Product.php';
				//	var_dump($citilink);
				foreach ( $product->all() as $k => $v ) {
					echo '<tr id="cid' . $v->citilinkid . '" data-cid="' . $v->citilinkid . '"">';
					print_r( '<td>' . $v->id . '</td>' );
					print_r( '<td>' . $v->title . '</td>' );
					print_r( '<td>' . $v->citilinkid . '</td>' );
					print_r( '<td>' . $v->citilinkurl . '</td>' );
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

        </div>
        <div class="row">
            <div class="justify-content-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong"
                        data-cid="">
                    Launch demo modal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<style>
    table {
        font-size: .7em;
    }
</style>
<script src="jquery-3.3.1.min.js"></script>
<script src="tether.min.js"></script>
<script src="bootstrap.min.js"></script>
<script>
    $('tr[id^="cid"]').on('click', function () {
        $('.modal-body').html('<H1>' + $(this).attr('data-cid') + '</H1>');
        $('button').attr('data-cid', $(this).attr('data-cid')).trigger('click');
        console.log($(this).attr('data-cid'));
    })
</script>
</body>
</html>
