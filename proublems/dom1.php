<?php
// Set the header content type xml 
header("content-type: application/xml;");

// Create dom document 
$xml = new DOMdocument();

// Get the id 
$id = time().rand(0,10000);

$newshottitle = 'Hello';
// Load the document 
$content = file_get_contents('data1.xml');

$xml->loadXML($content);

$screenshots= $xml->getElementsByTagName("break_fast")[0];

$title= $xml->createElement("food");

// In title create attribute 
$title->setAttribute('id', $id);

// Create element 
$name = $xml->createElement('name', 'sho1');
$price = $xml->createElement('price', 89);
$id = $xml->createElement('id', $id);

// Append the element to title 
$title->appendChild($name);
$title->appendChild($price);
$title->appendChild($id);

$screenshots->appendChild($title);

$newdata = $xml->saveXML();

file_put_contents('data1.xml', $newdata);

// Again get the content 
//$newcontent = file_get_contents('data1.xml');

// New Xml  load content 
//$newxml = loadXML($newcontent);

// Save
 
print $xml->saveXML();
?>