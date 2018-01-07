<?php
echo "Memory at the starting ".memory_get_usage()." <br/>";

// Get the file 
$content = file_get_contents('datas.json');

// Decode to array 
$records = json_decode($content, true);

$serchResult = [];

// Search String 
$searchstring = 'Bharat';

echo "<pre>";
print_R(ProductByCategory($records, 'first_name', $searchstring));
echo "</pre>";


function ProductByCategory($records, $index, $indexvalue)
{
	$data = array_filter(
		array_map(
			function($items) use (&$index, &$indexvalue)
			{
				if(isset($items[$index]) && strpos($items[$index],$indexvalue) !== false)
				{
					return $items;
				}
			}
		,$records));
		
		// Sort the array 
		sort($data);
		
		// Return the data 
		return $data;
}

echo "Memory at the end ".memory_get_usage()." <br/>";
?>