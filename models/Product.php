<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 23.01.2018
 * Time: 15:39
 */


class Product extends ActiveRecord\Model
{
	// explicit table name since our table is not "books"
	static $table_name = 'Product';

	// explicit pk since our pk is not "id"
	static $primary_key = 'id';

	// explicit connection name since we always want production with this model
	static $connection = 'production';

	// explicit database name will generate sql like so => db.table_name
	static $db = 'tst01';
}


//print_r(Product::first()->attributes());