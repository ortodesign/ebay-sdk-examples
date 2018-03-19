<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 14:44
 */
error_reporting( 0 );
//$q = json_decode( file_get_contents( 'php://input' ), true )["id"];
$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : null;
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

if ( $id ) {
	echo '['.json_encode( $parsed_citi::find($id)->to_array() ).']';
} else {
	$result = array();
	foreach ( $parsed_citi::all() as &$e ) {
		array_push( $result, $e->to_array() );
	}
	echo json_encode( $result );
}
