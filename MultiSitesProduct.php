<?php

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
	
	// Protected method 
	public $SearchedData = [];
	
	// Run the class with prams 
	public function __construct($UrlInArray, $string,$attributes)
	{
		/* Run the search string method */
		$this->SiteAttribute($attributes);
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
		$this->FindTheProduct($attributes);
		
		$this->ReturnArrayKeys($attributes);
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
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
	
	
	public function GetRemoteContent($UrlToSearch)
	{
			/* Initialize the curl request */
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $UrlToSearch);
			/* Set the header */
        	curl_setopt($ch, CURLOPT_HEADER, 0);
        	
        	/* Do not check to verify */
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        	
        	/* Return transfre is true for output data */
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
        	/* Execute curl request */
        	$output = curl_exec($ch);
        	
        	/* Close the curl request */       
       		curl_close($ch);
		
			/* If curl execute is not eqal to false */
        	if($output !== false )
        	{
        		 return htmlspecialchars($output);
        	}
		
		return false;
	}
	
	
	public function FindTheProduct($attributes)
	{
		/* Get the html content */
		$storeContent = $this->StoreContet;
		
		/* Set new domn documenbt */	
		$content = [];
		
		/* I have array key for site attributes */
		$siteAttributes = $this->ReturnArrayKeys($attributes);
		/* Access each content in array */
		
		/* Get the dom object */
		@$doc = new DOMDocument();
		
		/* Another object because same object can not use for another request */
		@$docDis = new DOMDocument();
		
		/* Access each content */
		foreach($storeContent as $key => $value)
		{
			/* Decode the html with dom object */
			@$doc->loadHTML(htmlspecialchars_decode($value));   
			
			/* Object, Class inside loop is not good but no option */
			$xpath = new DomXPath($doc);
			
			/* Check search request returning something */
			$ElementFound = $xpath->query($this->SiteAttribute($attributes)[$key]['price'])->length;
			
			/* Get all vandor logos */
			$logo 			= 	$this->GetNodeValue($this->SiteAttribute($attributes)[$key]['logo'],$xpath);
			
			/* Get the images */
			$image          = 	$this->GetNodeValue($this->SiteAttribute($attributes)[$key]['image'],$xpath);
			
			/* Get the discription */
			$discription 	=   $this->GetNodeValue($this->SiteAttribute($attributes)[$key]['discription'],$xpath);
			/* Check the currency */
			$price			= 	$this->GetNodeValue($this->SiteAttribute($attributes)[$key]['price'],$xpath);
				
			/* If price contain and latters */
			$price = preg_replace('/[A-Za-z ]/', '', $price);
			
			/* With experience found that in some link hostname is not included */
			if($this->filter_var_domain($logo) == false)
			{
				/* Append with hostname or ipaddress*/
				$logo = $key.$logo;
			} 
				
			// Check discription containe hostname as well 
			if($this->filter_var_domain($discription) == false)
			{
				/* Append with hostname or ipaddress*/
				$discription = $key.$discription;
			} 
				
			// Check discription containe hostname as well 
			if($this->filter_var_domain($image) == false)
			{
				/* Append with hostname or ipaddress*/
				$image = $key.$image;
			}
				
			/* Again send the request for product details */
			$detailsContents = $this->GetRemoteContent($discription);
			
			/* Discrition details */
			$discription_deatils = [];
			
			/**/
			$dis_attributes = $this->SiteAttribute($attributes)[$key]['discription-details'];
				
				
			$distitle = '';
			
			// Get each discription access @$docDis
			@$docDis->loadHTML(htmlspecialchars_decode($detailsContents));
		
			// Get new DomXpath object 
			$dis_xpath = new DomXPath(@$docDis);
			
			$data = [
						'logo' => trim($logo),
						'image' => trim($image),
						'discription' => trim($discription),
						'price' => trim($price),
						
					];
			
			// Loop each keys of discription 
			foreach($this->SiteAttribute($attributes)[$key]['discription-details'] as $diskey => $disvalue) {
			
				// Check if value is true 
				if($this->IsXPath($disvalue)) {
					
					// Set value to data key 
					$data[$diskey] = $this->GetNodeValue($this->SiteAttribute($attributes)[$key]['discription-details'][$diskey],$dis_xpath);
			
				} else {
				
					// Simply string what is set the associative array 
					$data[$diskey] =  $disvalue;
				}
			
			}
			
			
			// In the case of associative array if we need 
			/*
				$data = [
						'logo' => trim($logo),
						'image' => trim($image),
						'discription' => trim($discription),
						'price' => trim($price),
						'discription_details' => $discription_deatils
					];
			*/
				
			
					
				// Setting data in variable 
				$content[$key] = $data;
				/* Set the data to class property */
		
				// Set the class property 
				$this->content = $content; 
		
		}
	
	}
	
	public function GetNodeValue($object,$xpath) {
	
		// Check variable 
		if($object !== '') {
			
			// Check node length is greater then 0
			
			if($xpath->query($object)->length === 0) {
				
				return null;
				
			} else {
			
				// Get node value 
				//$nodevalue = $xpath->query($this->SiteAttribute($attributes)[$key]['currency'])->item(0)->nodeValue;
				$nodevalue = $xpath->query($object)->item(0)->nodeValue;
				
				// Return variable 
				return trim(preg_replace('/\s\s+/', ' ', $nodevalue));
			}
			
		}
		
		// Return false 
		return null;
	}
	
	public function GetNodeElmenets($object,$xpath) {
	
		// Check variable 
		if($object !== '') {
			
			// Check node length is greater then 0
			
			if($xpath->query($object)->length === 0) {
				
				return null;
				
			} else {
			
				// Get node value 
				//$nodevalue = $xpath->query($this->SiteAttribute($attributes)[$key]['currency'])->item(0)->nodeValue;
				$nodevalue = $xpath->query($object);
				
				// Return variable 
				return trim($nodevalue, "\t\n\r\0\x0B");
			}
			
		}
		
		// Return false 
		return null;
	}
	
	public function ReturnArrayKeys($attributes)
	{
			/* Get the html content */
		$SiteAttribute = $this->SiteAttribute($attributes);
		
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
	
	public function DOMinnerHTML(DOMNode $element) 
	{ 
		
		$innerHTML = ""; 
		$children  = $element->childNodes;

		foreach ($children as $child) 
		{ 
			$innerHTML .= $element->ownerDocument->saveHTML($child);
		}

		return htmlspecialchars($innerHTML); 
	} 
	
	public function ReturnHTMLChilds($elements) {

		// append element 
		$appendel = '';
	
		foreach ($elements as $table) 
		{ 
    		$appendel .= $this->DOMinnerHTML($table); 
		} 
	
		return $appendel;
	}	


	public function SiteAttribute($attributes)
	{	
		return $attributes;	
	}
	
	public function IsXPath($str)
	{
		// Get the first two letter
		$twoletter = substr($str,0,2);
	
		// Check if // 
		if($twoletter === '//') {
		
			return true;
		}
	
		return false;
	}
	
	function  filter_var_domain($domain)
	{
    	if(stripos($domain, 'http://') === 0) {
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


// Url in array 
$UrlInArray = 
				[
				'https://uae.souq.com/ae-en/{{@searchString}}/s/?as=1',
				'https://www.jumbo.ae/home/search?q={{@searchString}}',
				'https://uae.microless.com/search/?query={{@searchString}}',
				'https://www.carrefouruae.com/mafuae/en/search={{@searchString}}',
				'https://www.trobone.com/catalogsearch/result/?q={{@searchString}}',
				'https://www.buyondubai.com/search/search_term/{{@searchString}}',
				'https://www.virginmegastore.ae/en/search/?text={{@searchString}}',
				'http://www.erosdigitalhome.ae/home/search?q={{@searchString}}&sort_type=relevance',
				
				];

$attributes = [
							'uae.souq.com'
													=> 	[
											'logo' => '//img[@class="logo"]/@src',
											'image' => '//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src',
											'discription' => '//ul[@class="list-blocks"]/li/a/@href',
											'price' => "//h3[@class='itemPrice']",
											'discription-details' => [
																		'title' => '//div[@class = "small-12 columns product-title"]/h1',
																		'shipping' => '//strong[@class = "green-text"]',
																		'availability' => '//div[@class="unit-labels"]//b//span',
																		'condition' => '//dd[@class="unit-condition"]',
																		'warranty' => '//span[@id="stats-full"]',
																		'delivery-period' => '//small[@class="estimated"]//span',
																		'exchange-period' => '',
																		'review' => '//a[@class="linkToReviewsTab"]//span',
																		'product-discription' => '//div[@id="description-short"]//p',
																		'payment-method' => '["Master Card", "Visa Card", "COD"]',
																		'previous-price' => '',
																		'price' => '//h3[@class = "price is sk-clr1"]',
																		'currency' => '//span[@class="currency-text sk-clr1"]',
																		'social-share' => '//div[@class="opposite-direction band socialIconsWrapper"]',
																		'rating' => '//i[@class="star-rating-svg"]//i/@style',
																		'stock-left' => '//b[@class="txtcolor-alert xleft"]//span',
																		'discount' => '//span[@class="discount"]',
				
																		
																		
																	]
											
											],
							'www.jumbo.ae'
													=> 	[
											'logo' => '//div[@class="logo"]//a//img/@src',
											'image' => "//div[@id='content-slot']//div[@class='variant-image']//img/@src",
											'discription' => "//div[@id='content-slot']//span[@class='variant-title']//a/@href",
											'price' => "//div[@id='content-slot']//span[@class='variant-final-price']",
											'discription-details' => [
																		'title' => '//h1[@class="like-h3"]',
																		'shipping' => '//div[@class = "free_delivery_pdp_banner"]',
																		'availability' => '//span[@class="in-stock"]//span',
																		'condition' => '',
																		'warranty' => '',
																		'delivery-period' => '',
																		'exchange-period' => '//div[@id="exchange_policy"]',
																		'review' => '',
																		'product-discription' => '',
																		'payment-method' => '["Master Card", "Visa Card", "Paypal", "Payfort"]',
																		'previous-price' => '',
																		'price' => '//div[@class="our_price"]//span[@class = "m-w"]',
																		'currency' => '//div[@class="our_price"]//span[@class = "m-c c-aed"]',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => '//div[@id="nearest_store_finder"]',
				
																		
																		
																	],

										],
							'uae.microless.com'	
													=>	[
											'logo' => "//div[@id='header-logo']/@style",
											'image' => "//div[@id='search-results']//div[@class='product-image']//a//img/@src",
											'discription' => "//div[@id='search-results']//div[@class='product-title']//a/@href",
											'price' => "//div[@id='search-results']//div[@class='new-price']//span[@class='amount']",
											'discription-details' => [
																		'title' => '//h1[@class="product-title-h1"]//span',
																		'shipping' => '//div[@style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;"]//strong[position()=3]',
																		'availability' => '//div[@style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;"]//strong[position()=2]',
																		'condition' => '//div[@style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;"]//strong[position()=1]',
																		'warranty' => '//div[@class="no-ext-warranty"]//strong',
																		'delivery-period' => '',
																		'exchange-period' => '',
																		'review' => '',
																		'product-discription' => '', // Use this //h2[@class="title"]/following-sibling::*[1]
																		'payment-method' => '["Master Card", "Visa Card", "COD", "Abitcoin", "Bank Transfer", "Installment Payment"]',
																		'previous-price' => '',
																		'price' => '',
																		'currency' => '//span[@class="product-currency"]',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => '',
				
																		
																		
																	],

											],
							'www.carrefouruae.com' 	=>	[
											'logo' => "//a[@class='logo']/@href",
											'image' => "//div[@class='product']//a//div[@class='media']//img/@src",
											'discription' => "//div[@class='product']//p[@class='name']//a/@href",
											'price' => "//div[@class='product']//p[@class='finalPrice']",
											'discription-details' => [
																		'title' => '//div[@class="product-details"]/h1',
																		'shipping' => '//p[@class="free-shipping"]',
																		'availability' => '//p[@class="qty"]',
																		'condition' => '//p[@class="stock"]',
																		'warranty' => '',
																		'delivery-period' => '//p[@class="delivery"]',
																		'exchange-period' => '',
																		'review' => '',
																		'product-discription' => '',
																		'payment-method' => '["Master Card", "Visa Card", "COD"]',
																		'previous-price' => '',
																		'price' => '//p[@class="finalPrice"]',
																		'currency' => '//p[@class="finalPrice"]/span',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => '',
				
																		
																		
																	],
										],
							'www.trobone.com' 		=>	[
											'logo' => "//div[@class='logo col-lg-3 col-md-3 col-sm-12 col-xs-12']//a//img/@src",
											'image' => "//div[@class='products-grid']//div[@class='product-image']//a//img/@src",
											'discription' => "//div[@class='products-grid']//div[@class='product-image']//a/@href",
											'price' => "//div[@class='products-grid']//div[@class='price-box']//span[@class='price']",
											'discription-details' => [
																		'title' => '//div[@class="product-name"]/h1',
																		'shipping' => '//span[@class="icon-truck"]',
																		'availability' => '',
																		'condition' => '//div[@class="policy policy4"]',
																		'warranty' => '//div[@class="policy policy3"]',
																		'delivery-period' => '//div[@class="policy-content"]',
																		'exchange-period' => '//div[@class="policy policy1"]',
																		'review' => '',
																		'product-discription' => '//div[@class="short-description"]',
																		'payment-method' => '["Master Card", "Visa Card", "COD", "Western Union"]',
																		'previous-price' => '//span[@class="old-price"]/span[@class="price"]',
																		'price' => '//span[@class="special-price"]/span[@class="price"]',
																		'currency' => '',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => '',
				
																		
																		
																	],
			
										],
							'www.buyondubai.com'	=>	[
											'logo' => "//a[@class='fixed-header-visible additional-header-logo']//img/@src",
											'image' => "//div[@class='shop-grid grid-view']//div[@class='product-image']//a//img/@data-src",
											'discription' => "//div[@class='shop-grid grid-view']//a/@href",
											'price' => "//div[@class='shop-grid grid-view']//div[@class='price']//div[@class='current']",
											'discription-details' => [
																		'title' => '//h1[@class="product-title product-title-mobile"]',
																		'shipping' => '//span[@class="flash"]',
																		'availability' => '',
																		'condition' => '100 Geniunine Products',
																		'warranty' => '//h3[@class="product-subtitle"]',
																		'delivery-period' => 'Super Quick Delivery',
																		'exchange-period' => 'Easy Return Policy',
																		'review' => '',
																		'product-discription' => '//div[@class="product-description detail-info-entry"]',
																		'payment-method' => '["Master Card", "Visa Card", "COD", "Western Union", "Bank Transfer"]',
																		'previous-price' => '//div[@class="price detail-info-entry test"]/div/p',
																		'price' => '//div[@class="price detail-info-entry test"]/div[@class="current"]',
																		'currency' => '',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '//div[@class="price detail-info-entry test"]/small',
																		'click-and-collect' => '',
				
																		
																		
																	],

											],
							'www.virginmegastore.ae' =>	[
												'logo' => "//div[@class='simple-banner-component']//a//img/@src",
												'image' => "//ul[@class='product-listing product-grid']//li[@class='product-item']//a//img/@src",
												'discription' => "//ul[@class='product-listing product-grid']//div[@class='details']//a/@href",
												'price' => "//ul[@class='product-listing product-grid']//div[@class='priceContainer']//span[@class='price']",
												'discription-details' => [
																		'title' => '//h1[@class="name"]',
																		'shipping' => '//div[@id="free_ship"]',
																		'availability' => 'Check availability in stores',
																		'condition' => '',
																		'warranty' => '',
																		'delivery-period' => '',
																		'exchange-period' => '',
																		'review' => '',
																		'product-discription' => '',
																		'payment-method' => '["Master Card", "Visa Card", "Mestro","Cirrus","American Express","COD"]',
																		'previous-price' => '',
																		'price' => '//span[@class="price"]',
																		'currency' => '',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => 'Collect in store within 2 hours',
				
																		
																		
																	],

											],
				
							'www.erosdigitalhome.ae' => [
												'logo' => "//div[@class='logo']//a//img/@src",
												'image' => "//ul[@class='search-result-items']//div[@class='variant-image']//span//img/@src",
												'discription' => '//span[@class="variant-title"]/a/@href',
												'price' => '//span[@class="m-w"]',
												'discription-details' => [
																		'title' => '//div[@id="title"]/h1',
																		'shipping' => '',
																		'availability' => '//span[@class="in-stock"]',
																		'condition' => '',
																		'warranty' => '//div[@id="warranty"]',
																		'delivery-period' => '//span[@class="ships-in"]',
																		'exchange-period' => '',
																		'review' => '',
																		'product-discription' => '',
																		'payment-method' => '["Master Card", "Visa Card","Bank Transfer"]',
																		'previous-price' => '',
																		'price' => '//span[@class="m-c"]',
																		'currency' => '//span[@class="m-c c-aed"]',
																		'social-share' => '',
																		'rating' => '',
																		'review' => '',
																		'stock-left' => '',
																		'discount' => '',
																		'click-and-collect' => '',
				
																		
																		
																	],

											
			],
							
							
							
							
							
									
			
			];
		
$string = 'Apple iPhone 8 Plus';

$obj = new ComparePrice($UrlInArray, $string,$attributes);
echo "<pre>";
print_R($obj->content);
echo "<pre>";


?>