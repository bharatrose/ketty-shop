<?php
header('Content-type: application/xml');
// Set new dom document 
//$doc = new DomDocument;

// We need to validate our document before refering to the id
//$doc->validateOnParse = true;

// Load the xml file 
//$xmlstr = $doc->load('book.xml');
//$xmlstr = file_get_contents('data.xml');

// Load xml 

$micro = microtime(true);

// Replace the string 
$micro = str_replace('.','', $micro);

// append the string with random number 
$id = rand(0,100000).$micro.sha1($micro);

//$sxe = new SimpleXMLElement($xmlstr);

$sxe = simplexml_load_file('break-fast.xml');

if ($sxe === false) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
}


//$sxe->addAttribute('type', 'documentary');

$character = $sxe->addChild('food');

$character->addChild('name', 'Mr. Praser');
$character->addChild('price', '898');
$character->addChild('id', $id);
$newdata = $sxe->asXML();


file_put_contents('break-fast.xml', $newdata);

// Again get the content 
//$sxe = simplexml_load_file('break-fast.xml');
/* Block for testing emelemtn */

/* Block for testing emelemtn */
$xmlStr = file_get_contents('break-fast.xml');
$newdata = $sxe->asXML();
//$sxe = new SimpleXMLElement($xmlStr);
echo $newdata;


?>