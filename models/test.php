<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 23.01.2018
 * Time: 15:39
 */
class test extends ActiveRecord\Model
{
	static $validates_uniqueness_of = array(
		"unique_property"
	);
	public static function connection() {
		return parent::connection();
	}
}
