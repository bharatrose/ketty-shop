<?php

$searchsTring = urlencode(trim('apple macbook pro i5'));

/**/
		$ch = curl_init("https://en-ae.wadi.com/catalog/?q=$searchsTring&ref=search");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $output = curl_exec($ch);       
        curl_close($ch);


@$doc = new DOMDocument();
@$doc->loadHTML($output);   

$xpath = new DomXPath($doc);

$itemprice = $xpath->query("//div[@class='not-visible']");

/*
$output = file_get_contents("https://uae.microless.com/search/?query=$searchsTring");

*/
$json_data = $itemprice->item(0)->nodeValue;



$replacefirst = str_replace('window.pageData = window.pageData || []; window.pageData.push(','',$json_data);


$strsec = ');window.referrerData = window.referrerData || []; window.referrerData.push({"referrer":null});window.midnight_campaign_on = falsewindow.one_dirham_stock_available = undefinedwindow.imageCDN = "https://b.wadicdn.com";window.imageCDNServers = "https://b.wadicdn.com";window.requestURL = "/catalog/?q='.$searchsTring.'&ref=search";';


$replacesecound = str_replace($strsec, '', $replacefirst);

$object = json_decode($replacesecound, true);


// We juse need first index 

$firstrow = $object['data'][0];
echo "<pre>";
print_R($firstrow);
echo "</pre>";

// In serch found nothing then it's return null
/*
	If nothing found then 
	$firstrow == null
*/

/*
	Required data 
*/

if($firstrow !== null)
{
	
	$logo = 'https://b.wadicdn.com/phoenix/m-images/sprite_wadi-40dbaefa.png';
	$image = '';
	$title = $firstrow['name'];
	$discription = $firstrow['link'];
	$shipping = $firstrow['simples'][0]['deliveryTimeMax'];
	$currency = 'AED';
	$price = $firstrow['price'];
	
	// Put variable in array 
	/*
		'logo' =>$logo,
		'image' => $image,
		'title' =>$title,
		'shipping' => $discription,
		'discription' => $shipping,
		'currency' => $currency,
		'price => $price
	*/
	$data = [
				'logo' =>$logo,
				'image' => $image,
				'title' =>$title,
				'shipping' => $discription,
				'discription' => $shipping,
				'currency' => $currency,
				'price' => $price
		];
	
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}


exit();
if($itemprice->length === 0)
{
	// Exite the code 
	die("Unable to find product for $searchsTring.");
}


/*
$vandor_logos = $xpath->query('//img[@class="logo"]/@src')->item(0)->nodeValue;

$product_image = $xpath->query("//a[@class='img-bucket']");
$product_title =  $xpath->query("//h1[@class='itemTitle']");
$shipping = $xpath->query("//strong[@class='green-text']");
$discription_title = $xpath->query('//ul[@class="list-blocks"]/li/a/@href');
$currency = $xpath->query('//small[@class="currency-text sk-clr1 itemCurrency"]');
$grid_product_images = $xpath->query('//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src');

*/


// 'product-title' => '//h1[@class="itemTitle"]',
//$vandor_logo = $vandor_logos->item(0);
/*
$first_item = $itemprice->item(0);
$pimage = $product_image->item(0);
$ptitle = $product_title->item(0);
$pshipping = $shipping->item(0);
$pdiscription = $discription_title->item(0);
$currencyValue = $currency->item(0);
$grid_product_image = $grid_product_images->item(0);
*/
// How many element is found 
//$numberOfElemnt = $itemprice->length;

//$getchild = [];
// If would like to get each elements 
//for($i = 0; $i < $numberOfElemnt ; $i++)
//{
	//$getchild[] = $itemprice->item($i);
//}


/*
$data[]  = [
			'vandor_log' => $vandor_logos,
			'price' => $first_item->nodeValue,
			'title' => trim($ptitle->nodeValue),
			'shipping' => $pshipping->nodeValue,
			'view-details' => $pdiscription->nodeValue,
			'currency-code' => $currencyValue->nodeValue,
			'product-image' => $grid_product_image->nodeValue
		];
*/


echo "<pre>";
print_r($data);
echo "</pre>";
exit();


$ch1 = curl_init("https://www.jumbo.ae/home/search?q=$searchsTring");
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch1, CURLOPT_POST, 1);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, "");
$output1 = curl_exec($ch1);       
curl_close($ch1);




@$doc1 = new DOMDocument();
@$doc1->loadHTML($output1);   

$xpath1 = new DomXPath($doc1);

$itemprice1 = $xpath1->query("//div[@id='content-slot']//span[@class='price']");

//echo "<pre>";
//print_r($itemprice1);
//echo "</pre>";

/* Get the data */

/*

$p_image_tag = "//a[@class='img-bucket";
$p_title = "//h1[@class='itemTitle";
$p_shipping = "//strong[@class='green-text']";
$p_discription_title = '//ul[@class="list-blocks"]/li/a/@href';
$p_currency = '//small[@class="currency-text sk-clr1 itemCurrency"]';
$p_grid_product_images ='//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src';
*/
$vandor_logos1 = $xpath1->query("//a[@class='img-bucket']");
$product_image1 = $xpath1->query("//a[@class='img-bucket']");
$product_title1 =  $xpath1->query("//div[@id='content-slot']//span[@class='variant-title']");

//$shipping1 = $xpath1->query("//strong[@class='green-text']");

$discription_title1 = $xpath1->query("//div[@id='content-slot']//span[@class='variant-title']//a/@href");

$currency1 = $xpath1->query("//div[@id='content-slot']//span[@class='variant-final-price']//span[@class='m-w']//span[@class='m-c c-aed']");

$product_image1 = $xpath1->query("//div[@id='content-slot']//div[@class='variant-image']//img/@src");


$vandor_logo1 = 'https://cf1.s3.souqcdn.com/public/style/img/en/souq-logo-v2.png';
$first_item1 = $itemprice1->item(0);
$pimage1 = $product_image1->item(0);
$ptitle1 = $product_title1->item(0);
$pshipping1 = 'N/A';
$pdiscription1 = $discription_title1->item(0);
$currencyValue1 = $currency1->item(0);
$grid_product_image1 = $product_image1->item(0);


$data[] = [
			'vandor_log' => $vandor_logo1,
			'price' => trim($first_item1->nodeValue),
			'title' => trim($ptitle1->nodeValue),
			'shipping' => $pshipping1,
			'view-details' => $pdiscription1->nodeValue,
			'currency-code' => $currencyValue1->nodeValue,
			'product-image' => $grid_product_image1->nodeValue
		];
			

$numberOfElemnt1 = $itemprice1->length;

//$getchild1 = [];
// If would like to get each elements 
/*
for($i = 0; $i < $numberOfElemnt1 ; $i++)
{
	$getchild1[] = $itemprice1->item($i);
}
*/


echo "<pre>";
print_r($data);
echo "</pre>";

//$conent = file_get_contents('https://www.ebay.com/sch/i.html?_from=R40&_trksid=p2050601.m570.l1313.TR0.TRC0.H0.Xapple+macbook+pro.TRS0&_nkw=apple+macbook+pro&_sacat=0');

//echo $conent;







?>