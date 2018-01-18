<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 18.01.2018
 * Time: 13:28
 */
$query = 'iphone 7';
$jso = file_get_contents( 'https://www.citilink.ru/search/?text=iphone%207&p=7', "r" );
var_dump(  $jso );
//https://www.citilink.ru/search/?text=iphone%207&p=7