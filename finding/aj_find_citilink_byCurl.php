<?php
error_reporting( 0 );
if ( isset($_GET['target']) ) {

	$target = $_GET['target'];
//	$target = $_POST['target'] ? $_POST['target'] : 'https://www.citilink.ru/catalog/mobile/cell_phones/357582/';
//$target = 'https://www.citilink.ru/catalog/mobile/cell_phones/357582/';
//echo '<pre>';
//echo($target);
//echo '</pre>';
//
//simple proxy
$url = $_GET['target'];
//echo $url . '<br>';
$proxy = '91.193.143:913128';
//$proxyauth = 'user:password';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
// curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
//	echo $curl_scraped_page;
	     echo  '<br>';
//echo $curl_scraped_page;
//	$html = file_get_contents( $target );
	$html = $curl_scraped_page;

	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( $html );
	libxml_clear_errors();
	$finder = new DOMXPath( $dom );
	//По сути ищем готовый json с данными из исходного кода страницы в тегах <script> и обрезаем строку регулярками.
	$rows   = $finder->query( "//script[contains(text(),'pageData = ')]" )->item( 0 )->nodeValue;;

//$doc->getElementsByTagName('script'); //->item(0)->nodeValue
//echo '<pre>';
	preg_match( '/pageData = {"pageType".+;/', $rows, $matches );
//print_r(json_decode(substr($matches[0],10,strlen($matches)-1)));
	print_r( substr( $matches[0], 10, strlen( $matches ) - 1 ) );
	echo ',';
//echo '</pre>';

} else {
	echo 'no GET parameters<br>';
//	$f = file_get_contents( 'citiLinkProductPage.json', "r" );
//	print_r( $f );
}

////simple proxy
//$url = 'http://dynupdate.no-ip.com/ip.php';
//$proxy = '127.0.0.1:8888';
////$proxyauth = 'user:password';
//
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL,$url);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
////curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
//// curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
//// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_HEADER, 1);
//$curl_scraped_page = curl_exec($ch);
//curl_close($ch);
//
//echo $curl_scraped_page;