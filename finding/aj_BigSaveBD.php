<?php
error_reporting( 0 );

if ( ! ( isset( $_POST ) && isset( $_POST['directive'] ) ) ) {
	die( 'no post or directive' );
} else {
	$new    = ( $_POST['directive']['type'] == 'new' ) ? true : false;
	$update = ( $_POST['directive']['type'] == 'update' ) ? true : false;
	$save   = ( $_POST['directive']['type'] == 'save' ) ? true : false;
	$delete = ( $_POST['directive']['type'] == 'delete' ) ? true : false;
	$data   = $_POST['data'];
}

require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);

ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
	$cfg->set_connections( $connections );
} );

class Oops extends ActiveRecord\Model {
	static $table_name = 'oops';
	static $connection = 'production';
}

$pre_product = new Oops;

if ( $save || $delete || $update || $new ) {
	foreach ( $data as $item ) {
		if ( $new ) {
			$preItem = $pre_product::create( $item );
		}
		if ( $delete ) {
			$preItem = $pre_product::delete( intval( $item['id'] ) );
		}
		if ( $update || $save ) {
			$preItem = $pre_product::find( intval( $item['id'] ) );
			$preItem->update_attributes( $item );
		}
		var_dump( $preItem );
	}
}

