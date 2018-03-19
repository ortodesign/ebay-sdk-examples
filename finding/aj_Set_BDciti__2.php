<?php

error_reporting( 0 );

require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);

ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
	$cfg->set_connections( $connections );
} );


class Parsed_citi extends ActiveRecord\Model {
	static $table_name = 'parsed_citi';
	static $connection = 'production';
}

$parsed_citi = new Parsed_citi;


if ( ! ( isset( $_GET ) & $_GET['delete'] =='ids' ) ) {

	$q = ( isset( $_POST ) & $_POST['data'] ) ? $_POST['data'] : die( 'no data' );
//$q = (isset($_POST)) ? $_POST : die('no data');



	foreach ( $q as $item ) {
		$citiItem = $parsed_citi::find( intval( $item['id'] ) );
		$citiItem->update_attributes( $item );
		var_dump( $citiItem );
	}

} else if ( isset( $_GET ) & $_GET['delete'] =='ids' ) {
	$qd = array_map( function($value) { return (int)$value; }, $_POST['ids'] );
	$parsed_citi::table()->delete(array('id' => $qd));
//	var_dump($qd);
}