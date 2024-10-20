<?

# <IMG src="usd.php" width="88" height="41" border="0"/>
# выводит PNG 88x41

$scripturl = 'https://cbr.ru/scripts/XML_dynamic.asp';

$date_1=date('d/m/Y', time()-172800);

$date_2=date('d/m/Y');

// Код бумаги, можно получить инфо по ссылке: http://www.cbr.ru/scripts/XML_val.asp?d=0
// XSD-shema:  https://cbr.ru/StaticHtml/File/92172/Valuta.xsd
$currency_code='R01235';

# URL с параметрами
$requrl = "{$scripturl}?date_req1={$date_1}&date_req2={$date_2}&VAL_NM_RQ={$currency_code}";



$url = $requrl;


$xmlStr = file_get_contents($url);

$xml = new SimpleXMLElement($xmlStr);



$con = json_encode($xml); 
$newArr = json_decode($con, true); 



####################################################################################################################
# Этот код работает
// $characters = 'https://iss.moex.com/iss/history/engines/stock/markets/bonds/boards/TQOB/securities.json';
// $characters = file_get_contents($characters);
// $characters = json_decode($characters, true);
// // var_dump($characters);
// $characters = isset($characters['history']) ? $characters['history'] : array();
// var_dump($characters);

// if ($characters) {
//     foreach ($characters as $character) {
//         $buyer = isset($character['metadata']) ? $character['metadata'] : '';
//         $item = isset($character['item']) ? $character['item'] : array();
//         $name = isset($item['name']) ? $item['name'] : '';
//         var_dump($buyer);
//     }
// }
#END Этот код работает
####################################################################################################################







$last = Getfloat( $newArr['Record'][1]['Value']);				
$prev = Getfloat( $newArr['Record'][0]['Value']);
$date = $date_2;
			
$rate = sprintf("%.2f",$last);		

 $delta = (($last>$prev)?"+":"").sprintf("%.2f",$last-$prev);






$string = "{$date}: 1USD={$rate} RUR ({$delta})";
$h = ImageFontHeight(2)+2;
$w = ImageFontWidth(2)*strlen($string)+2;









header("Content-type: image/png"); 
$im = @ImageCreate(88, 41) or die("Cannot do ImageCreate()");


$bg = ImageColorAllocate($im, 240, 255, 233);
$fg = ImageColorAllocate($im, 0, 0, 0);
$fg2 = ImageColorAllocate($im, 120, 0, 0);
$bdr = ImageColorAllocate($im, 224,224,224);
$bdr2 = ImageColorAllocate($im, 160,160,160);


$x = (88-ImageFontWidth(2)*strlen($date))/2;
ImageString($im, 2, $x, 2, $date, $fg);


ImageString($im, 2, 25, 14, "USD/RUR", $fg2);


ImageString($im, 2, 6, 25, "$rate ($delta)", $fg2);


ImageRectangle($im, 0, 0, ImageSX($im)-2, ImageSY($im)-2, $bdr);
ImageLine($im,ImageSX($im)-1,1,ImageSX($im)-1, ImageSY($im)-1,$bdr2);
ImageLine($im,1,ImageSY($im)-1,ImageSX($im)-1, ImageSY($im)-1,$bdr2);


ImagePNG($im);


ImageDestroy($im);




function Getfloat($str) {

	if(strstr($str, ",")) {
  
	  $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
  
	  $str = str_replace(",", ".", $str); // replace ',' with '.'
  
	}
  
	
  
	if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
  
	  return floatval($match[0]);
  
	} else {
  
	  return floatval($str); // take some last chances with floatval
  
	}
  
  }

  

?>