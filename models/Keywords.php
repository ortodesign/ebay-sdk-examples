<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 23.01.2018
 * Time: 15:39
 */
require_once  __DIR__ . '/../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
class Keywords extends ActiveRecord\Model
{
	// explicit table name since our table is not "books"
	static $table_name = 'keywords';

	// explicit pk since our pk is not "id"
	static $primary_key = 'id';

	// explicit connection name since we always want production with this model
	static $connection = 'production';

	// explicit database name will generate sql like so => db.table_name
	static $db = 'tst01';
}

$connections = array(
	'development' => 'mysql://tst01:tst@localhost/tst01',
	'test' => 'mysql://tst01:tst@localhost/tst01',
	'production' => 'mysql://tst01:tst@localhost/tst01'
);

// initialize ActiveRecord
ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
	$cfg->set_model_directory( __DIR__ . '/../models');
	$cfg->set_connections($connections);
});

//print_r(Product::first()->attributes());