<?php
$memoryinmb = memory_get_usage();

echo "Memory at the starting $memoryinmb <br/>";
// Get the file 
$content = file_get_contents('datas.json');

// Decode to array 
$records = json_decode($content, false);

$serchResult = [];

// Search String 
$searchstring = 'Bharat';

foreach($records as $key => $value)
{
	if(strpos($value->first_name, $searchstring) !== false)
	{
		$searchResult[] = $records[$key];
	}
}

echo "<pre>";
print_r($searchResult);
echo "</pre>";
$memoryinmb = memory_get_usage();
echo "Memory at the end $memoryinmb <br/>";
?>