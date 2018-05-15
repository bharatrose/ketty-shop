<?php

class SingleSiteProduct {

	// Holds search string 
	protected $searchString;
	
	// Holds sites url
	protected $siteURL;
	
	// Holdes sites attributes 
	protected $siteAttributes;
	
	// Search URl 
	protected $searchURL;
	
	protected $SearchResult = [];
	
	// While accessing the object 
	public function __construct($searchString,$siteUrl, $siteAttributes) {
		
		// Replace the url to search string 
		$Search = preg_replace('/{{@searchString}}/', urlencode($searchString),$siteUrl);
		
		// Setting variable to class property 
		$this->searchURL = $Search;
		
		// Set the site attribute 
		if(is_array($siteAttributes)) {
			
			$this->siteAttributes = $siteAttributes;
		}
		
		// Search Poduct 
		$this->SearchProduct();
		
		// Get the product 
		$this->GetProduct();
		
	}
	
	// Get the remote content using curl extension 
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
	
	// Get the nodes value 
	public function GetNodeValue($object,$xpath, $n) 
	{
	
		// Check variable 
		if($object !== '') {
			
			// Check node length is greater then 0
			
			if($xpath->query($object)->length === 0) {
				
				return null;
				
			} else {
			
				// Get node value 
				//$nodevalue = $xpath->query($this->SiteAttribute($attributes)[$key]['currency'])->item(0)->nodeValue;
				$nodevalue = $xpath->query($object)->item($n)->nodeValue;
				
				// Return variable 
				return trim(preg_replace('/\s\s+/', ' ', $nodevalue));
			}
			
		}
		
		// Return false 
		return null;
	}
	
	// If path is valid xpath
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
	
	public function SearchProduct() 
	{
	
		// Get the url to search 
		$url = $this->searchURL;
		
		
		// Check variable if not false  
		if($this->GetRemoteContent($url) !== false ) {
			
			
			// Get the content 
			$result = $this->GetRemoteContent($url);
			
			// Get dom object 
			@$doc = new DOMDocument();
			
			// Decode the return html docuemnt with dom and load document
			@$doc->loadHTML(htmlspecialchars_decode($result));   
			
			// Create new xpath object 
			$xpath = new DomXPath($doc);
			
			// We required another object as well
			@$docDis = new DOMDocument();
			
			// Defining array variable 
			$var = [];
			
			// Get site attributes 
			$attribute = $this->siteAttributes;
			
			// We can not use recrusive loop thefore static loop
			foreach($attribute as $firstKey => $firstValue) {

				// Count price discription 
				$coundis  = $xpath->query($attribute[$firstKey]['price'])->length;
				
				
				for($i = 0; $i < $coundis; $i++) {

				foreach($attribute[$firstKey] as $key => $value) {
	
				// Static variable for product discription 
				if($key === 'discription') {
		
					// Get static variable for product link 
					$discription = $xpath->query($value)->item($i)->nodeValue;
				}
				
				
				if($key === 'discription-details') {
		
					// Get each discription xpath 
					$detailsContents = $this->GetRemoteContent($discription);
					
					// Loop each data again 
					$discription_url = $value;
			
					// Holdes product discription
					$takediscription = [];
			
					// Loading html content
					@$docDis->loadHTML(htmlspecialchars_decode($detailsContents));   
					
					// Get new Domxpath object 
					$dis_xpath = new DomXPath(@$docDis);
			
					// Loop the each content 
					foreach($discription_url as $disKey => $disValue) {
				
					// If xpath is valid 
					if($this->IsXPath($disValue)) {
				
					// Take the discritpion 
					//$takediscription[$disKey] =  $this->GetNodeValue($disValue,$dis_xpath, 0);
					
					$var1[$disKey] = $this->GetNodeValue($disValue,$dis_xpath, 0);
				
					} else {		
						
						// Xpath could be single string 
						$var1[$disKey] = $disValue;
					}
				
				
			}
			
				// Get the prodcut discription 
				//$var1[$key] =  $takediscription;
		
				} else {
			
				// Set the value 
				$var1[$key] =  $xpath->query($value)->item($i)->nodeValue;
			}
		
		}
	
				$var[] = $var1;
	
				// While developing the the application
				//if($i == 3) { break;}
	
			}

				$domain[$firstKey] = $var;
				
				// Set to search result 
				$this->SearchResult = $domain;
				
				
			}
		}
	}
	
	public function GetProduct() {
	
		// Check if variable is array and more then 
		if( count ($this->SearchResult) > 0) {
			
			return $this->SearchResult;
		}
	}
	
}


$searchString = urlencode('Apple iPhone 8 Plus');
$siteAttributes = [ 
				'https://uae.souq.com/ae-en/' => [
					'price' => '//span[@class="itemPrice"]',
					'discription' => '//a[@class="itemLink block sPrimaryLink"]/@href',
					'discription-details' => [
													'title' => '//div[@class = "small-12 columns product-title"]/h1',
													'shipping' => '//strong[@class = "green-text"]',
													'availability' => '//div[@class="unit-labels"]//b//span',
													'condition' => '//dd[@class="unit-condition"]',
													'warranty' => '//span[@id="stats-full"]',
													'delivery-period' => '//small[@class="estimated"]//span',
													'exchange-period' => '',
													'payment-method' => '["Master Card", "Visa Card", "COD"]',
													'review' => '//a[@class="linkToReviewsTab"]//span',
													'product-discription' => '//div[@id="description-short"]//p',
													'price' => '//h3[@class = "price is sk-clr1"]',
													'currency' => '//span[@class="currency-text sk-clr1"]',
													'social-share' => '//div[@class="opposite-direction band socialIconsWrapper"]',
													'rating' => '//i[@class="star-rating-svg"]//i/@style',
													'stock-left' => '//b[@class="txtcolor-alert xleft"]//span',
													'discount' => '//span[@class="discount"]',
																		
												]

				],

			];

$siteUrl = "https://uae.souq.com/ae-en/{{@searchString}}/s/?as=1";

$obj = new SingleSiteProduct($searchString,$siteUrl, $siteAttributes);

echo "<pre>";
print_r($obj->GetProduct());
echo "<pre>";
?>
