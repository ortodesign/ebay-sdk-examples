<?php
echo "<?xml version='1.0' encoding='ISO-8859-1' ?>\n";
// do not change anything above this line.

// Configuration settings
$appID = "Enter Your Application ID";

$apiEndPoint = "http://open.api.ebay.com/shopping";
$apiVersion = 663;
$responseEncoding = 'XML';
$opName = "GetSingleItem";

$itemID = null;
if(isset($_POST["postcheck"]) && "posted" == $_POST["postcheck"])
{
//	$debug = "here";
	$itemID = $_POST["itemID"];
	$requiredParams = "appid=" . $appID . "&callname=" . $opName . "&version=" . $apiVersion . "&keywords=" . $searchString . "&ItemID=" . $itemID;
	$optionalParams = "responseencoding=" . $responseEncoding;
	$request = $apiEndPoint . "?" . $requiredParams . "&" . $optionalParams;
	$xmlResponse = simplexml_load_file($request);
	// should check for latency - new DateTime();
	
	$item = array();
	$item['ID'] = (string)$xmlResponse->Item->ItemID;
	$item['timeStamp'] = new DateTime($xmlResponse->Timestamp);
	$item['endTime'] = new DateTime($xmlResponse->Item->EndTime);
	$item['timeLeft'] = (string)$xmlResponse->Item->TimeLeft;
	$item['title'] = $xmlResponse->Item->Title;
}

function timeLeft(array $target)
{
	$return_value = "";
	$diff = $target['endTime']->diff($target['timeStamp']);
    $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals
   
    $format = array();
    if($diff->y !== 0) {
        $format[] = "%y ".$doPlural($diff->y, "year");
    }
    if($diff->m !== 0) {
        $format[] = "%m ".$doPlural($diff->m, "month");
    }
    if($diff->d !== 0) {
        $format[] = "%d ".$doPlural($diff->d, "day");
    }
    if($diff->h !== 0) {
        $format[] = "%h ".$doPlural($diff->h, "hour");
    }
    if($diff->i !== 0) {
        $format[] = "%i ".$doPlural($diff->i, "minute");
    }
    if($diff->s !== 0) {
        if(!count($format)) {
            return "less than a minute ago";
        } else {
            $format[] = "%s ".$doPlural($diff->s, "second");
        }
    }
   
    // Prepend 'since ' or whatever you like from the calling function
    return $diff->format(implode (" ",$format)); 

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>DTS example: Shopping:GetSingleItem and nicely formated TimeLeft.</title>
	<style >
	<!--
		label {
		width: 7em;
		float: left;
		}
		input {
		width: 20em;
		}
		td {
		padding: .2em;
		}
	--></style>
</head>
<body>
	<h1>DTS example</h1>
	<h2>Using the Shopping API's GetSingleItem and format the TimeLeft nicely.</h2>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" id="mainForm" method="post">
		<p>
			<label for="appIDctrl">Application ID</label><input size="50" id="appIDctrl" name="appID" type="text" value="<?php echo $appID; ?>"  /><br />
			<label for="itemIDctrl">Item ID</label><input id="itemIDctrl" name="itemID" type="text" value="<?php echo $itemID; ?>"  /> <br />
			<input id="postctrl" name="postcheck" type="hidden" value="posted"  />
<!--			<label for="appIDctrl">Application ID</label><input id="appIDctrl" name="appID" type="text" value="<?php echo $appID; ?>"  />-->
<!--			<label for="appIDctrl">Application ID</label><input id="appIDctrl" name="appID" type="text" value="<?php echo $appID; ?>"  />-->
<!--			<label for="appIDctrl">Application ID</label><input id="appIDctrl" name="appID" type="text" value="<?php echo $appID; ?>"  />-->
<!--			<label for="appIDctrl">Application ID</label><input id="appIDctrl" name="appID" type="text" value="<?php echo $appID; ?>"  />-->
			<label for="send">&nbsp;</label><input id="send" name="send" type="submit" value="Submit"  />
		</p>
	</form>
	<?php if (!empty($item)) :?>
	<table border="1">
		<tr>
			<th>Item ID</th>
			<th>Title</th>
			<th>Time Left</th>
			<th>Pretty Time Left</th>
		</tr>
		<tr>
			<td><?php echo $item['ID']; ?></td>
			<td><?php echo $item['title']; ?></td>
			<td><?php echo $item['timeLeft']; ?></td>
			<td><?php echo timeLeft($item); ?></td>
		</tr>
	</table>
	<hr />
	<h3>Response XML</h3>
	<pre><?php echo "\n"; ?>
<?php echo htmlspecialchars($xmlResponse->asXML()) . "\n"; ?>
	</pre>
	<?php endif; ?>
	<?php if (!empty($debug)) :?>
	<pre>
	<?php echo '$debug=' . $debug . "\n";?>
	<?php echo '$_POST=' . var_dump($_POST) . "\n"?>
	</pre>
	<?php endif; ?>
</body>
</html>