<?php
//error_reporting( 0 );
if ( isset($_GET['target']) ) {

//	$url = $_GET['target'] ? $_GET['target'] : 'https://www.citilink.ru/catalog/mobile/cell_phones/357582/';
	$url = $_GET['target'];
//	echo $url; echo '<br>';
	//proxy = '91.193.143:913128';
	//$proxyauth = 'user:password';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
	//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$curl_scraped_page = curl_exec($ch);
	curl_close($ch);
//	echo  '<br>';
	$html = $curl_scraped_page;
//	var_dump($html);

	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( $html );
//	echo  '<pre>';
//	var_dump($dom);

	libxml_clear_errors();
	$finder = new DOMXPath( $dom );
	//По сути ищем готовый json с данными из исходного кода страницы в тегах <script> и обрезаем строку регулярками.
//	$rows   = $finder->query( "//script[contains(text(),'pageData = ')]" )->item( 0 )->nodeValue;;
//	preg_match( '/pageData = {"pageType".+;/', $rows, $matches );
//	var_dump($finder);
	$rows   = $finder->query( "//script[contains(text(),'var utag_data = ')]" )->item( 0 )->nodeValue;;
	$pic = $finder->query( "(//span/@imgzoompic[contains(., 'ProductImageCompressAll1280')])[1]" )->item( 0 )->nodeValue;
	print_r('{picUrl:"');
	print_r($pic);
	print_r('",');

//	var_dump($rows);
//
//	echo  '</pre>';
//	print_r( substr( $rows, 11+12, strlen( $rows ) - 31 ) );
//	echo strrpos( $rows , '}');echo '<br>';
	$NE_utag_data = ( substr( $rows, 12+12, strrpos( $rows , '}')-12-12+1  ) );
//	echo  '<pre>';
//	var_dump(json_decode($NE_utag_data));
	print_r($NE_utag_data);
//	echo  '</pre>';
//	preg_match( '/utag_data = .+};/', $rows, $matches );
//	var_dump($matches);
//	print_r( substr( $matches[0], 11, strlen( $matches ) - 1 ) );
//	echo ',';

} else {
	echo 'no GET parameters<br>';
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