<?php

$searchstring = '';

/*	On the basis for different domain name 
	They use differnet url encode method 
	for example 
	$vandor_logos = $xpath->query('//img[@class="logo"]/@src');
	$product_image = $xpath->query("//a[@class='img-bucket']");
	$product_title =  $xpath->query("//h1[@class='itemTitle']");
	$shipping = $xpath->query("//strong[@class='green-text']");
	$discription_title = $xpath->query('//ul[@class="list-blocks"]/li/a/@href');
	$currency = $xpath->query('//small[@class="currency-text sk-clr1 itemCurrency"]');
	$grid_product_images = $xpath->query('//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src');
	$itemprice = $xpath->query("//h3[@class='itemPrice']");

	
*/

$records = 
array(
   	 	array(
        'vandor-url' => 'https://uae.souq.com/ae-en/macbook-pro/s/',
        'vandor-logo-url' => 'https://cf1.s3.souqcdn.com/public/style/img/en/souq-logo-v2.png',
        'product-title' => '//h1[@class="itemTitle"]',
        'shipping' =>'//h3[@class="green-text"]',
        'product-image' => '//a[@class="img-bucket"]',
        'price' => '//h3[@class="itemPrice"]'
    	),
    	
    	
	);


class ComparePrice
{
	/* Will hold url */
	public $vandor_url;
	
	/* Vandor logo url */
	public $vandor_logo_url;
	
	/* Product image */
	public $product_image;
	
	/* Product title */
	public $product_title;
	
	/* Product price */
	public $product_price;
	
	/* Shipping */
	public $shipping;
	
	/* Public function */
	public $content = [];
	
	/* Urls to search in string  */
	public $UrlToSearch = [];
	
	/* Store the content */
	public $StoreContet = [];
	
	
	
	// Run the class with prams 
	public function __construct($UrlInArray, $string)
	{
		/* Run the search string method */
		
		/* Url encode */
		$string = urlencode($string);
		
		// Loop the url 
		for($i = 0; $i < count($UrlInArray); $i++)
		{
			/* Setting url in variable */ 
			 $searcurl = str_replace('{{@searchString}}', $string, $UrlInArray[$i]);
			 
			 /* Validate url */
			 if (filter_var($searcurl, FILTER_VALIDATE_URL)) {
			 	
			 	/* Set url in class property array */
			 	$this->UrlToSearch[] = $searcurl;
			 }
			 
			 
			 
		}
		
		
		/* Run remote search string */
		$this->RemoteRequest($this->UrlToSearch);
		
		/* Rund dom element */
		$this->FindTheProduct();
		
		$this->ReturnArrayKeys();
	}
	
	
	public function RemoteRequest($UrlToSearch)
	{
		/* Get the url to perform */
		foreach($UrlToSearch as $key)
		{
			/* Initialize the curl request */
			$ch = curl_init($key);
			
			/* Set the header */
        	curl_setopt($ch, CURLOPT_HEADER, 0);
        	
        	/* Using post method */
        	curl_setopt($ch, CURLOPT_POST, 1);
        	
        	/* Do not check to verify */
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        	
        	/* Return transfre is true for output data */
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        	
        	/* Post the fields */
        	curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        	
        	/* Execute curl request */
        	$output = curl_exec($ch);
        	
        	/* If curl execute is not eqal to false */
        	if($output !== false )
        	{
        		/* Get solid url */
        		$parseUrl = parse_url($key);
        		/* Parurl */
        		$this->StoreContet[$parseUrl['host']] = htmlspecialchars($output);
        	}
        	/* Close the curl request */       
       		curl_close($ch);
		}
	}
	
	public function FindTheProduct()
	{
		/* Get the html content */
		$storeContent = $this->StoreContet;
		
		/* Set new domn documenbt */	
		$content = [];
		
		/* I have array key for site attributes */
		$siteAttributes = $this->ReturnArrayKeys();
		/* Access each content in array */
		
		foreach($storeContent as $key => $value)
		{
			@$doc = new DOMDocument();
			
			@$doc->loadHTML(htmlspecialchars_decode($value));   

			$xpath = new DomXPath($doc);
			
			for($a = 0; $a < count($siteAttributes); $a++)
			{
				$xpath->query($this->SiteAttribute()[$key][$siteAttributes[$a]])->item(0)->nodeValue;
			}
			/* Get all vandor logos */
			$logo 			= 	$xpath->query($this->SiteAttribute()[$key]['logo'])->item(0)->nodeValue;
			$image          = 	$xpath->query($this->SiteAttribute()[$key]['image'])->item(0)->nodeValue;
			$title 			=   $xpath->query($this->SiteAttribute()[$key]['title'])->item(0)->nodeValue;
			$shipping 		=   $xpath->query($this->SiteAttribute()[$key]['shipping'])->item(0)->nodeValue;
			$discription 	=   $xpath->query($this->SiteAttribute()[$key]['discription'])->item(0)->nodeValue;
			$currency 		=   $xpath->query($this->SiteAttribute()[$key]['currency'])->item(0)->nodeValue;
			$price			= 	$xpath->query($this->SiteAttribute()[$key]['price'])->item(0)->nodeValue;
			
			/* Setting new value to the array data */
			
			/* Get first index arrray keys */
			$data = [
						'logo' => trim($logo),
						'image' => trim($image),
						'title' => trim($title),
						'shipping' => trim($shipping),
						'discription' => trim($discription),
						'currency' => trim($currency),
						'price' => trim($price)
					];
					
			$content[$key] = $data;

		}
		
		/* Set the data to class property */
		
		$this->content = $content;  
		
		

		
	}
	
	public function ReturnArrayKeys()
	{
			/* Get the html content */
		$SiteAttribute = $this->SiteAttribute();
		
		/* Reset the array pointer */
		reset($SiteAttribute);
		
		/* Get first Key */
		$first_key = key($SiteAttribute);

		/* Get the keys */
		$first_key_val = $SiteAttribute[$first_key];
		
		/* Get array keys */
		$keys = array_keys($first_key_val);
		
		/* Return the variable */
		return $keys;
		
	}
	
	public function SiteAttribute()
	{	
			$attributes = [
							'uae.souq.com'
										=> [
											'logo' => '//img[@class="logo"]/@src',
											'image' => '//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src',
											'title' =>"//h1[@class='itemTitle']",
											'shipping' => "//strong[@class='green-text']",
											'discription' => '//ul[@class="list-blocks"]/li/a/@href',
											'currency' => '//small[@class="currency-text sk-clr1 itemCurrency"]',
											'price' => "//h3[@class='itemPrice']"
											
											],
							'www.jumbo.ae'
										=> [
											'logo' => '//div[@class="logo"]//a//img/@src',
											'image' => "//div[@id='content-slot']//div[@class='variant-image']//img/@src",
											'title' => "//div[@id='content-slot']//span[@class='variant-title']",
											'shipping' => "//div[@id='content-slot']//div[@id='free_shipping']",
											'discription' => "//div[@id='content-slot']//span[@class='variant-title']//a/@href",
											'currency' => "//div[@id='content-slot']//span[@class='variant-final-price']//span[@class='m-w']//span[@class='m-c c-aed']",
											'price' => "//div[@id='content-slot']//span[@class='price']"
											]			
						];
	return $attributes;
	}
	

}


// Url in array 
$UrlInArray = [
				'https://uae.souq.com/ae-en/{{@searchString}}/s/?as=1',
				'https://www.jumbo.ae/home/search?q={{@searchString}}',
		];

$string = 'Apple MacBook Pro 2016 Laptop With Touch Bar';

$obj = new ComparePrice($UrlInArray, $string);
echo "<pre>";
print_R($obj);
echo "<pre>";

?>