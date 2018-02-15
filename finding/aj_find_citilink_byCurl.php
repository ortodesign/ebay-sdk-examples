<?php
error_reporting( 0 );
if ( $_POST['target'] ) {

	$target = $_POST['target'];
//	$target = $_POST['target'] ? $_POST['target'] : 'https://www.citilink.ru/catalog/mobile/cell_phones/357582/';
//$target = 'https://www.citilink.ru/catalog/mobile/cell_phones/357582/';
//echo '<pre>';
//echo($target);
//echo '</pre>';
//

	$html = file_get_contents( $target );

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
//echo '</pre>';

} else {
	$f = file_get_contents( 'citiLinkProductPage.json', "r" );
	print_r( $f );
}
