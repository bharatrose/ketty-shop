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
		$string = urlencode(strtolower($string));
		
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
        	
        	/* Do not check to verify */
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        	
        	/* Return transfre is true for output data */
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        	
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
				//$xpath->query($this->SiteAttribute()[$key][$siteAttributes[$a]])->item(0)->nodeValue;
			}
			
			/* Check search request returning something */
			$ElementFound = $xpath->query($this->SiteAttribute()[$key]['price']);
			
			echo "<pre>";
			print_r($ElementFound);
			echo "</pre>";
			
			/* Count and validate */
			if($ElementFound->length === 0)
			{
			
				// Set all Variable to null 
				$logo = NULL;
				$image = NULL;
				$title = NULL;
				$shipping = NULL;
				$discription = NULL;
				$currency = NULL;
				$price = NULL;
				
			} else {
			
				/* Get all vandor logos */
				$logo 			= 	$xpath->query($this->SiteAttribute()[$key]['logo'])->item(0)->nodeValue;
				$image          = 	$xpath->query($this->SiteAttribute()[$key]['image'])->item(0)->nodeValue;
				$title 			=   $xpath->query($this->SiteAttribute()[$key]['title'])->item(0)->nodeValue;
				$shipping 		=   $xpath->query($this->SiteAttribute()[$key]['shipping'])->item(0)->nodeValue;
				
				$discription 	=   $xpath->query($this->SiteAttribute()[$key]['discription'])->item(0)->nodeValue;
				$currency 		=   $xpath->query($this->SiteAttribute()[$key]['currency'])->item(0)->nodeValue;
				$price			= 	$xpath->query($this->SiteAttribute()[$key]['price'])->item(0)->nodeValue;
			
			// Check discription containe hostname as well 
			if($this->filter_var_domain($discription) == false)
			{
				$discription = $key.$discription;
			} 
				
			// Remove 
			$shipping = preg_replace("/\s+/", " ", $shipping);
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
				/* Set the data to class property */
		
				$this->content = $content; 
			
			
			}
			
			

		}
	
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
											'price' => "//div[@id='content-slot']//span[@class='variant-final-price']"
											],
											
							'uae.microless.com'		
											=>
											[
											'logo' => "//div[@id='search-results']//div[@class='product-image']//a//img/@src",
											'image' => "//div[@id='search-results']//div[@class='product-image']//a//img/@src",
											'title' => "//div[@id='search-results']//div[@class='product-title']//a",
											'shipping' => "//div[@id='search-results']//div[@class='bottom']",
											'discription' => "//div[@id='search-results']//div[@class='product-title']//a/@href",
											'currency' => "//div[@id='search-results']//div[@class='pull-left1']",
											'price' => "//div[@id='search-results']//div[@class='pull-left1']//span[@class='amount']"
											]	
						];
	return $attributes;
	}
	
	
	function  filter_var_domain($domain)
	{
    if(stripos($domain, 'http://') === 0)
    {
        $domain = substr($domain, 7); 
    }
     
    ///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
    if(!substr_count($domain, '.'))
    {
        return false;
    }
     
    if(stripos($domain, 'www.') === 0)
    {
        $domain = substr($domain, 4); 
    }
     
    $again = 'http://' . $domain;
    
    return filter_var ($again, FILTER_VALIDATE_URL);
}

	

}

echo "<h3>Memory At the begning ".memory_get_usage()."</h3>";

// Url in array 
$UrlInArray = [
				'https://uae.souq.com/ae-en/{{@searchString}}/s/?as=1',
				'https://www.jumbo.ae/home/search?q={{@searchString}}',
				'https://uae.microless.com/search/?query={{@searchString}}'
				
		];

$string = 'Apple MacBook Pro';

$obj = new ComparePrice($UrlInArray, $string);
echo "<pre>";
print_R($obj);
echo "<pre>";

unset($obj);

echo "<h3>Memory At the end ".memory_get_usage()."</h3>";

/* Next get the information about */
/*
https://en-ae.wadi.com/catalog/?q=apple+mac+book&ref=search
*/

?>