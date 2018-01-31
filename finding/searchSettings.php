<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 31.01.2018
 * Time: 15:35
 */
?>
<?php include 'header.php'; ?>

	<body>
<?php include 'menu.php'; ?>

<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test'        => 'mysql://tst01:tst@localhost/tst01',
	'production'  => 'mysql://tst01:tst@localhost/tst01'
);
ActiveRecord\Config::initialize( function ( $cfg ) use ( $connections ) {
	$cfg->set_connections( $connections );
} );

class Settings extends ActiveRecord\Model {
	static $table_name = 'settings';
	static $connection = 'production';
}

$_POSTsSettings = new  Settings;
//print_r('all done');

?>
