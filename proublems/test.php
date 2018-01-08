<?php

//echo "<h1> Menory At Starting ". memory_get_usage()."</h1>";

$records = array(
    array(
        'id' => 2,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date' => '2017-09-13',
        'category' => 'Laptops & Notebooks',
    ),
    array(
        'id' => 4,
        'first_name' => 'Sally',
        'last_name' => 'Smith',
        'date' => '2017-09-19',
        'category' => 'Stationery',
    ),
    array(
        'id' => 1,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
        'date' => '2017-09-11',
        'category' => 'Laptops & Notebooks',
    ),
    array(
        'id' => 3,
        'first_name' => 'Peter',
        'last_name' => 'Doe',
        'date' => '2017-09-19',
        'category' => 'Stationery',
    )
    ,
    array(
        'id' => 78,
        'first_name' => 'Mac Book',
        'last_name' => 'Doe',
        'date' => '2017-09-20',
        'category' => 'Laptops & Notebooks',
    ),
    array(
        'id' => 79,
        'first_name' => 'Mac Book Pro',
        'last_name' => 'Smith',
        'date' => '2017-09-21',
        'category' => 'Stationery',
    ),
    array(
        'id' => 80,
        'first_name' => 'Mac Book Retina Display',
        'last_name' => 'Jones',
        'date' => '2017-09-19',
        'category' => 'Laptops & Notebooks',
    ),
    array(
        'id' => 81,
        'first_name' => 'Dell Inspiron i3',
        'last_name' => 'Doe',
        'date' => '2017-09-29',
        'category' => 'Laptops',
    )
);



/*
function getSum($records)
{
	 return array_sum(array_map(function($item) { 
    return $item['id']; 
}, $records));

}
*/
//echo getSum($records);

// Get all string implode user name 
//echo getSum($records);

/* Get the name imploded */
function GetAllName($records)
{
	return implode(',',array_map(function($items)
	{
		return $items['first_name'];
	}, $records));
}

/* Sum value of id */
function SumValueOfId($records)
{
	return array_sum(array_map(function($items){
		return $items['id'];
	}, $records));
}

//echo GetAllName($records);

//echo SumValueOfId($records);

// Get the largest number from array
function GetMaxRecord($records)
{
	return max(array_map(function($items){
		
		return $items;
	}, $records));
}

function GetMinRecord($records)
{
	return min(array_map(function($items){
		
	return $items;
	
	},$records));
}
//echo "<pre>";
//print_R(SortByPrice($records));
//echo "</pre>"; 
 
//echo "<pre>";
//print_R(GetMinRecord($records));
//echo "</pre>"; 

function ProductByDESC($data, $method)
{
	return ($data["id"] >= $method["id"]) ? -1 : 1;
}

//usort($records, "HighToLowFetch");

//echo "<pre>";
//print_R($records);
//echo "</pre>"; 

function ProductByASC($data, $method)
{
	return ($data["id"] <= $method["id"]) ? -1: 1;
}



//usort($records, "ProductByASC");
//echo "<pre>";
//print_R($records);
//echo "</pre>"; 
function ProudctByDateASC($data, $method)
{
	return ($data["date"] <= $method["date"]) ? -1: 1;
}


//usort($records, "ProudctByDateASC");
//echo "<pre>";
//print_R($records);
//echo "</pre>"; 

/* Fetch prdouct by price range */
//echo "<pre>";
//print_R(get_defined_functions ());
//echo "</pre>"; 
function ProductByCategory($records,$index, $indexvalue)
{
	
	// Set the variable 
	$data = array_filter (
	array_map(function($items) use (&$index,&$indexvalue)
	{
		
		// Set the index and index value 
		if(isset($items[$index]) && $items[$index] == $indexvalue)
		{
			// Return the array column
			return $items;
		}
		
	}, $records));
	
	// Sort the array
	sort($data);
	
	// Return the that 
	return $data;
}

/* This is new file*/

//usort($records, "ProductByCategory");
//echo "<pre>";
//$var = ProductByCategory($records, 'category','Stationery');
//print_r($var);
//echo "</pre>";
//echo "<h1> At the end of script memory usage ". memory_get_usage()."</h1>";

//$load = sys_getloadavg();
//$var = ProductByCategory($records, 'category','Stationery');
//print_r($load);
//echo "</pre>";

//echo php_strip_whitespace(__FILE__);
// Newlines are considered whitespace, and are removed too:
//do_nothing();

/*
$vandor_logos = $xpath->query('//img[@class="logo"]/@src');
$product_image = $xpath->query("//a[@class='img-bucket']");
$product_title =  $xpath->query("//h1[@class='itemTitle']");
$shipping = $xpath->query("//strong[@class='green-text']");
$discription_title = $xpath->query('//ul[@class="list-blocks"]/li/a/@href');
$currency = $xpath->query('//small[@class="currency-text sk-clr1 itemCurrency"]');
$grid_product_images = $xpath->query('//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src');

*/

/*
$vandor_logos1 = $xpath1->query("//a[@class='img-bucket']");
$product_image1 = $xpath1->query("//a[@class='img-bucket']");
$product_title1 =  $xpath1->query("//div[@id='content-slot']//span[@class='variant-title']");

//$shipping1 = $xpath1->query("//strong[@class='green-text']");

$discription_title1 = $xpath1->query("//div[@id='content-slot']//span[@class='variant-title']//a/@href");

$currency1 = $xpath1->query("//div[@id='content-slot']//span[@class='variant-final-price']//span[@class='m-w']//span[@class='m-c c-aed']");

$product_image1 = $xpath1->query("//div[@id='content-slot']//div[@class='variant-image']//img/@src");


$vandor_logo1 = 'https://cf1.s3.souqcdn.com/public/style/img/en/souq-logo-v2.png';
*/

$attributes = [
							'uae.souq.com'
										=> [
											'logo' => '//img[@class="logo"]/@src',
											'image' => '//a[@class="img-bucket img-link itemLink sPrimaryLink"]/img/@data-src',
											'title' =>"//h1[@class='itemTitle']",
											'shipping' => "//strong[@class='green-text']",
											'discription' => '//ul[@class="list-blocks"]/li/a/@href',
											'currency' => '//small[@class="currency-text sk-clr1 itemCurrency"]'
											
											],
							'www.jumbo.ae'
										=> [
											'logo' => '//div[@class="logo"]//a//img/@src',
											'image' => "//div[@id='content-slot']//div[@class='variant-image']//img/@src",
											'title' => "//div[@id='content-slot']//span[@class='variant-title']",
											'shipping' => "//strong[@class='green-text']",
											'discription' => "//div[@id='content-slot']//span[@class='variant-title']//a/@href",
											'currency' => "//div[@id='content-slot']//span[@class='variant-final-price']//span[@class='m-w']//span[@class='m-c c-aed']",
											]			
						];
						

echo "<pre>";
print_r($attributes);
echo "</pre>";

?>