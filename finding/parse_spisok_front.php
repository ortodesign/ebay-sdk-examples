<?php
ini_set( 'max_execution_time', 1200 );
//echo 'start';
require_once 'functions.php';
//$spisok = json_decode(file_get_contents( 'jsons/spisok0222.json' ,"r"));
//$spisok = json_encode($spisok);require_once 'functions.php';
$spisok  = file_get_contents( 'jsons/spisok0222.json', "r" );
$already = file_get_contents( 'jsons/p001.json', "r" );
//$pisok = htmlspecialchars( json_encode( $spisok ) );
////$url = 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php';
//$url = 'aj_find_citilink_byCurl.php?';
//$all=array();
//echo '[';
//foreach ($spisok as &$s){
//	sleep(rand(2,5));
//	echo file_get_contents( 'http://localhost:8081/?' . urlencode( $url . 'target='. $s->citilinkURL  ), "r" );
//
//
//
//
////	$data = array('target' => $s->citilinkURL);
////	$options = array(
////		'http' => array(
////			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
////			'method'  => 'POST',
////			'content' => http_build_query($data)
////		)
////	);
////	$context  = stream_context_create($options);
////	$result = file_get_contents($url, false, $context);
////	if ($result === FALSE) { echo 'error parse'; }
////	array_push($all,$result);
////	echo (json_encode($result));
////	echo ',';
//
//
//
//}
//echo ']';

//echo '<pre>';
//echo (json_encode($all));
//echo '</pre>';
//echo 'end';
?>

<?php $pagetitle = 'Парсим с сити';
//include 'header.php'; ?>
<body>
<div id="point">

</div>

<?php include 'jsLibs.php'; ?>

<script>
    //var inJson = $.parseJSON('<?php //echo $spisok; ?>//');
    var inJson = <?php echo $spisok; ?>;
    var alreadyJson = <?php echo $already; ?>;
    var inJsonURLs = inJson.map(function (i) {
       return i.citilinkURL;
    });
    var alreadyURLs = alreadyJson.map(function (i) {
       return i.productUrl;
    });
    // $('#point').html(inJson);
    function sleep(miliseconds) {
        var currentTime = new Date().getTime();

        while (currentTime + miliseconds >= new Date().getTime()) {
        }
    }
    //
    // console.log(inJsonURLs);
    // console.log(alreadyURLs);
    var t = [];
    // var son = JSON.parse(inJson);
    // $('#point').append('[');
    inJson.forEach(function (item) {
        // alreadyURLs.forEach(function (aItem) {
        // console.log(item.citilinkURL);
            if (!(alreadyURLs.indexOf(item.citilinkURL) !== -1)) {
                t.push(item);
                // sleep(300);
                $.ajax({
                    url: 'http://ebay-sdk-examples/finding/aj_find_citilink_byCurl.php?' + 'target=' + item.citilinkURL,
                    // data: data,
                    success: function (data) {
                        console.log(data);
                        $('#point').append(data);
                    },
                    // dataType: dataType
                });

            };


            // console.log(item);

        // });
    });
    // console.log(t);

    // $('#point').append(']');


    // $('#point').html(inJson);
</script>


</body>
</html>


