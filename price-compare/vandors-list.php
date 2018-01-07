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
		
		//echo "<pre>";
		//print_R(array_keys($storeContent));
		//echo "</pre>";
		
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
											'logo' => "//div[@id='header-logo']/@style",
											'image' => "//div[@id='search-results']//div[@class='product-image']//a//img/@src",
											'title' => "//div[@id='search-results']//div[@class='product-title']//a",
											'shipping' => "//div[@id='search-results']//div[@class='free-shipping']",
											'discription' => "//div[@id='search-results']//div[@class='product-title']//a/@href",
											'currency' => "//div[@id='search-results']//div[@class='new-price']",
											'price' => "//div[@id='search-results']//div[@class='new-price']//span[@class='amount']"
											],
											
							'www.carrefouruae.com' =>
										[
											'logo' => "//a[@class='logo']/@href",
											'image' => "//div[@class='product']//a//div[@class='media']//img/@src",
											'title' => "//div[@class='product']//p[@class='name']//a",
											'shipping' => "//div[@class='product']",
											'discription' => "//div[@class='product']//p[@class='name']//a/@href",
											'currency' => "//div[@class='product']//p[@class='finalPrice']//span",
											'price' => "//div[@class='product']//p[@class='finalPrice']"
										],
										
							'www.trobone.com' =>
										[
											'logo' => "//div[@class='logo col-lg-3 col-md-3 col-sm-12 col-xs-12']//a//img/@src",
											'image' => "//div[@class='products-grid']//div[@class='product-image']//a//img/@src",
											'title' => "//div[@class='products-grid']//div[@class='product-info']//div[@class='product-name']//a",
											'shipping' => "//div[@class='products-grid']//div[@class='product-image']//a/@href",
											'discription' => "//div[@class='products-grid']//div[@class='product-image']//a/@href",
											'currency' => "//div[@class='products-grid']//div[@class='price-box']//span[@class='price']",
											'price' => "//div[@class='products-grid']//div[@class='price-box']//span[@class='price']"
										],
										
							'www.buyondubai.com'=>	
										[
											'logo' => "//a[@class='fixed-header-visible additional-header-logo']//img/@src",
											'image' => "//div[@class='shop-grid grid-view']//div[@class='product-image']//a//img/@data-src",
											'title' => "//div[@class='shop-grid grid-view']//div[@class='product-image']//a//img/@alt",
											'shipping' => "//div[@class='shop-grid grid-view']//div[@class='discount-label']",
											'discription' => "//div[@class='shop-grid grid-view']//a/@href",
											'currency' => "//div[@class='shop-grid grid-view']//div[@class='price']//div[@class='current']",
											'price' => "//div[@class='shop-grid grid-view']//div[@class='price']//div[@class='current']"
											],
							'www.virginmegastore.ae' =>
											
											[
												'logo' => "//div[@class='simple-banner-component']//a//img/@src",
												'image' => "//ul[@class='product-listing product-grid']//li[@class='product-item']//a//img/@src",
												'title' => "//ul[@class='product-listing product-grid']//li[@class='product-item']//a/@title",
												'shipping' => "//ul[@class='product-listing product-grid']//li[@class='product-item']//a/@title",
												'discription' => "//ul[@class='product-listing product-grid']//div[@class='details']//a/@href",
												'currency' => "//ul[@class='product-listing product-grid']//div[@class='priceContainer']//span[@class='price']",
												'price' => "//ul[@class='product-listing product-grid']//div[@class='priceContainer']//span[@class='price']"
											],
								
							'www.erosdigitalhome.ae' =>
							
											[
												'logo' => "//div[@class='logo']//a//img/@src",
												'image' => "//ul[@class='search-result-items']//div[@class='variant-image']//span//img/@src",
												'title' => "//ul[@class='search-result-items']//div[@class='variant-image']//span//img/@title",
												'shipping' => "//ul[@class='search-result-items']//div[@class='shipping_cost']//b",
												'discription' => "//ul[@class='search-result-items']//div[@class='variant-image']//a/@href",
												'currency' => "//ul[@class='search-result-items']//span[@class='variant-final-price']//span[@class='m-c c-aed']",
												'price' => "//ul[@class='search-result-items']//span[@class='variant-final-price']//span[@class='m-w']"
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
				'https://uae.microless.com/search/?query={{@searchString}}',
				'https://www.carrefouruae.com/mafuae/en/search={{@searchString}}',
				'https://www.trobone.com/catalogsearch/result/?q={{@searchString}}',
				'https://www.buyondubai.com/search/search_term/{{@searchString}}',
				'https://www.virginmegastore.ae/en/search/?text={{@searchString}}',
				'http://www.erosdigitalhome.ae/home/search?q={{@searchString}}&sort_type=relevance'
				
				
		];

$string = 'Apple MacBook Pro core i7';

$obj = new ComparePrice($UrlInArray, $string);
echo "<pre>";
print_R($obj->content);
echo "<pre>";

unset($obj);

echo "<h3>Memory At the end ".memory_get_usage()."</h3>";

/* Next get the information about */
/*
https://www.buyondubai.com/search/search_term/{{@searchString}}

[
'logo' => "//a[@class='fixed-header-visible additional-header-logo']/@href",
'image' => "//div[@class='shop-grid grid-view']//div[@class='product-image']//a//img/@data-src",
'title' => "//div[@class='shop-grid grid-view']//a",
'shipping' => "//div[@class='shop-grid grid-view']//div[@class='discount-label']",
'discription' => "//div[@class='shop-grid grid-view']//a/@href",
'currency' => "//div[@class='shop-grid grid-view']//div[@class='price']//div[@class='current']",
'price' => "//div[@class='shop-grid grid-view']//div[@class='price']//div[@class='current']"
]
*/
?>