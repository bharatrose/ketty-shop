<?php
/* Thig is working start to developing */
/* In this class if everthing is done then */
/* Remember to return 1 becaues javascript expecting 1*/
/* We are only showing this message */


/* Class must not exists */
class UploadProduct
{
	/* Define the class property */
	protected $productname;
	protected $discription;
	protected $shortdiscription;
	protected $availabledatefrom;
	protected $availabledateto;
	protected $sku;
	protected $price;
	protected $taxclass;
	protected $status;
	protected $images = array();
	
	/* Meta Line */
	
	protected $metatitle;
	protected $metakeywords;
	protected $metadescription;
	
	
	/* Connection */
	protected $connection;
	
	
	/* Product images */
	protected $productimage = array();
	
	protected $data = array();
	
	/* String data for updating */
	protected $updatetodata = array();
	
	/* Using megic method for distribution of element value */
	
	protected $productallimg = array();
	
	/* Setting id */
	protected $id;
	
	protected $imgdir;
	
	/* Product by id and sku */
	protected $pbik;
	
	protected $fetchprice;
	
	/* Storing session data */
	protected $cartsessiondata;
	
	// Paypal related methods 
	protected $PaypalAPIButton;
	protected $GetPaypalAPIButton;
	// Set paypal Email address
	protected $PaypalEmail = "bharatrose3-facilitator@gmail.com";
	// Paypal notify url 
	protected $p_notify_url = "https://localhost/newproject3/my_ipn.php";
	// Where to return after posting data to paypal
	protected $p_return = "https://localhost/newproject3/papal-checkout-complete";
	// If cancel by the paypal which page to show 
	protected $p_cbt = "https://localhost/newproject3/paypal_cancel.php";
	protected $p_cancel_return = "XXX";
	// Which curren to use for payment purpose
	protected $p_Ic = "US";
	// Currence code for payment 
	protected $p_currency_code = "USD";
	
	/* Handel total amount in cart */
	public $totalItems;
	public $totalamount;
	
	/* Holds currency code */
	public $currency;
	public $currencycode;
	public $currencyvalue;
	
	/* Data by price range property */
	public $DataByPriceRange = array();
	
	/* Additional property for ketty clothing */
	
	public $special_price;
	public $available_items;
	public $origin;
	public $lining;
	public $material;
	public $condition;
	public $style;
	public $season;
	public $product_feature;
	public $productLable;
	public $colors;
	public $sizes;
	public $buttoncolor;
	public $avaibility;
	public $collectionu;
	public $weight;
	
	
	public function UploadingProduct()
	{
		$this->AcccepReqest();
		$this->WriteDocument();
		
	}
	
	/* Validate system configuration */
	public function SytemConfiguration()
	{		
		
		/* Check zend enginte */
		if(zend_version() < 2)
		{
			/* Error */
			printf("%s \r\n","<div class = 'note alert-denger'><p>To Run the script zend engine minimum 2.0 required.</p></div>");
			
			/* Return false */
			return false;
				
		}
		
		/* Check php version */
		if(phpversion() < 5.5)
		{
		
			/* Error */
			printf("%s \r\n","<div class = 'note alert-denger'><p>To Run the script php engine minimum 5.5 required.<p></div>");
			
			/* Return false */
			return false;
		}
		
		/* Return true */
		return true;
	}
	
	
	/* Acception http request */
	public function AcccepReqest()	
	{
		
		/* Get the all request ready */
		$productname 		=		$_REQUEST["name"];		
		$description 		=		$_REQUEST["description"];
		$shortdiscription 	= 		$_REQUEST["short-description"];
		$availabledatefrom 	= 		$_REQUEST["available-date-from"];
		$availabledateto 	= 		$_REQUEST["available-date-to"];
		$sku 				= 		$_REQUEST["sku"];
		$price 				= 		$_REQUEST["price"];
		$taxclass 			= 		$_REQUEST["taxclass"];
		$status 			= 		$_REQUEST["status"];
		
		$special_price 		=		$_REQUEST["special_price"];
		$available_items	=		$_REQUEST["available_items"];
		$origin				=		$_REQUEST["origin"];
		$lining 			=		$_REQUEST["lining"];
		$material			=		$_REQUEST["material"];
		$condition			=		$_REQUEST["condition"];
		$style 				=		$_REQUEST["style"];
		$season 			=		$_REQUEST["season"];
		$product_feature	= 		$_REQUEST["product_feature"];
		$productLable 		=		$_REQUEST["productLable"];
		$colors 			=		$_REQUEST["colors"];
		$sizes 				=		$_REQUEST["sizes"];
		$buttoncolor		= 		$_REQUEST["buttoncolor"];
		$avaibility 		=		$_REQUEST["avaibility"];
		$collectionu 		=		$_REQUEST["collection"];
		$weight 			=		$_REQUEST["weight"];
			
		
		/* Check product name */
		$reg = '/^[a-zA-Z0-9 ]{1,100}$/';
		
		$pn = $this->ValidData($reg, $productname, "<div class = 'note alert-danger'>Product name must be wuithin $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		
		/* Check special price */
		$reg = '/[\d ]{1,100}/';
		
		$pn = $this->ValidData($reg, $special_price, "<div class = 'note alert-danger'>Product special price must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Check available items */
		$reg = '/[\d ]{1,100}/';
		
		$pn = $this->ValidData($reg, $available_items, "<div class = 'note alert-danger'>Product available itmes must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Checking lining */
		/* Check available items */
		$reg = '/[\w ]{1,100}/';
		
		$pn = $this->ValidData($reg, $lining, "<div class = 'note alert-danger'>Product lining must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Check material */
		$reg = '/[\d\w ]{1,100}/';
		
		$pn = $this->ValidData($reg, $material, "<div class = 'note alert-danger'>Product material must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Check condition */
		if($condition == '')
		{
			return printf("%s \r\n","<div class = 'note alert-danger'>Please select condition.</div>");			
		}
		
		/* Check style */
		$reg = '/[\w\d ]{1,100}/';
		
		$pn = $this->ValidData($reg, $style, "<div class = 'note alert-danger'>Product style must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Checking seson */
		$reg = '/[\w\d ]{1,100}/';
		
		$pn = $this->ValidData($reg, $season, "<div class = 'note alert-danger'>Product season must be between $reg.</div>");
		
		if($pn !== true)
		{
			return $pn;
		}
		
		/* Check product feature */
		$reg = '/[\w\d ]{1,100}/';
		
		if($product_feature == '')
		{
			return printf("%s \r\n","<div class = 'note alert-danger'>Please select product feature.</div>");
		}
		
		/* check if color is not empty */
		$reg = '/^#[\w\d]{6}$/'; 
		$neddle = ',';
		
		$checkcolor = $this->checkHexa($reg, $colors, ',') ;
		
		if($checkcolor !== true)
		{
			return printf("%s \r\n", "<div class = 'note alert-danger'>$checkcolor.");
			
		}		
		
		/* Check sizes */
		$sizesinserver = array('xs','s','m','l','xl','xxl','xxxl');
		
		/* Check size */
		
		$checksize = $this->checkSizes($sizes , ',',$sizesinserver);
		
		
		
		if($checksize !== true)
		{
			return printf("%s \r\n", "<div class = 'note alert-danger'>$checksize</div>");
		}	
		
		/* Check button color */
		$btncolor = $this->checkHexa($reg, $buttoncolor,',');
		
		if($btncolor !== true)
		{
			return printf("%s \r\n", "<div class = 'note alert-danger'>$btncolor</div>");
		}
		
		
		
		/* Check avaibility */
		if($avaibility == '')	
		{
			printf("%s \r\n","<div class = 'note alert-danger'>Please select avaibility.</div>");
			return false;
		}
		
		// regression 
		$reg = '/[\w ]{1,100}/';
		
		if(!preg_match($reg,$weight))
		{
			return printf("%s \r\n", "<div class = 'note alert-danger'>Please enter weight.</div>");
			
		}
		
		/* Special price must be less then original price */
		if($price < $special_price)
		{
			return printf("%s \r\n", "<div class = 'note alert-danger'>Special price must be less or equal to price.</div>");
			
		}
		/* All all the value to class variable */
		$this->productname 			= htmlspecialchars($productname);
		$this->discription 			= htmlspecialchars($description);
		$this->shortdiscription 	= htmlspecialchars($shortdiscription);
		$this->availabledatefrom 	= htmlspecialchars($availabledatefrom);
		$this->availabledateto 		= htmlspecialchars($availabledateto);
		$this->sku 					= htmlspecialchars($sku);
		$this->price 				= htmlspecialchars($price);
		$this->taxclass 			= htmlspecialchars($taxclass);
		$this->status 				= htmlspecialchars($status);
		
		
		/* Additional for ketty */
		$this->special_price		= htmlspecialchars($special_price);
		$this->available_items 		= htmlspecialchars($available_items);
		$this->origin 				= htmlspecialchars($origin);
		$this->lining 				= htmlspecialchars($lining);
		$this->material 			= htmlspecialchars($material);
		$this->condition			= htmlspecialchars($condition);
		$this->style 				= htmlspecialchars($style);
		$this->season 				= htmlspecialchars($season);
		$this->product_feature 		= htmlspecialchars($product_feature);
		$this->productLable 		= htmlspecialchars($productLable);
		$this->colors 				= htmlspecialchars($colors);
		$this->sizes 				= htmlspecialchars($sizes);
		$this->buttoncolor 			= htmlspecialchars($buttoncolor);
		$this->avaibility 			= htmlspecialchars($avaibility);
		$this->collectionu 			= htmlspecialchars($collectionu);
		$this->weight 				= htmlspecialchars($weight);
		
		return true;
	}
	
	

	/* Checking size by exploding string */
	public function checkSizes($string, $neddle,$arr)
	{
		if($string != '')
		{
			/* Check if comma exist */
			if(strpos($string,$neddle))
			{
				/* Explod the color  */
				$eachcolor = explode($neddle,$string);	
				
				/* Check there is no difference */
				if(count(array_diff($eachcolor, $arr)) > 0)
				{
					/* Get the invalid sizes */
					return "Invalid sizes ".implode(',',array_diff($eachcolor,$arr))."";
				}		
				
				
			}			
			elseif(!in_array($string,$arr))
			{
				return "Invalid sizes $string";
			}
		
		}
		else
		{
			return 'please enter size.';
		}
		
		return true;
		
	}
	
	/* Method check hexa string */
	public function checkHexa($reg, $string,$neddle)
	{
		if($string != '')
		{
			/* Check if comma exist */
			if(strpos($string,$neddle) &&  strpos($string,$neddle) == 7)
			{
					/* Explod the color  */
					$eachcolor = explode($neddle,$string);					
					
					/* Check color its hexa string */
					
					$allvalid = true;
					/* Loop each data */
					foreach($eachcolor as $key => $value)
					{
						/* Check */
						if(!preg_match($reg, $value))
						{
							return "Invalid hexa color $value.";
						}
					
						
					}
					
					return true;
					
				/* Return string */
				
			}
			elseif(preg_match($reg,$string))
			{	
				return true;
				
			}
			else
			{
				return "Invalid hexa color $string.";
			}
			
			
		}
		else
		{
			return 'Please enter color';
		}		
		
	}
	/* Throwing message error */
	public function getHexaColor($reg, $string,$neddle)
	{
		if($string != '')
		{
			/* Check if comma exist */
			if(strpos($string,$neddle) &&  strpos($string,$neddle) == 7)
			{
					/* Explod the color  */
					$eachcolor = explode($neddle,$string);					
					
					/* Check color its hexa string */
					
					$allvalid = true;
					/* Loop each data */
					foreach($eachcolor as $key => $value)
					{
						/* Check */
						if(!preg_match($reg, $value))
						{
							return "Invalid hexa color $value.";
						}
					
					
						
					}
					
					return $string;
					
				/* Return string */
				
			}
			elseif(preg_match($reg,$string))
			{	
				return $string;
				
			}
			else
			{
				return "Invalid hexa color $string.";
			}
			
			
		}
		else
		{
			return 'Please enter color';
		}		
		
	}
	/* Get colors for product discription */
	
	public function ValidData($reg, $data, $message)
	{
			if(!preg_match($reg,$data))
			{
					/* Return message */
					return printf("%s \r\n",$message);
			}
			
			return true;
	}
	/* Collecting the values  and setting to property */
	public function DBConfiguration()
	{
		/* Get the loaded extension */
		$gle = get_loaded_extensions();
		
		/* We are using mongodb */
		if(!in_array('mongo', $gle))
		{
			/* Error */
			printf("%s \r\n","<div class = 'note alert-denger'><p>No mongo extension available.</p></div>");
			return  false;
		}
		
		/* Create new connection */
		$mongocon = new MongoClient("localhost:27017");
		
		/* Return the connection */ 
		return $mongocon;
	}
	
	/* Uploading file system */
	
	public function UploadFileOop($filename, $directory, $sku)
	{
		
		/* Check file must be set */
		if(!isset($_FILES[$filename]["name"]))
		{
			/* Error */
			printf("%s \r\n","<div class = 'note alert-denger'><p>Please select an image.</p></div>");
			return false;
		}
		
		/* Cound the images */
		if(count($_FILES[$filename]["name"]) < 0)
		 {
		 	/* Error */
			printf("%s \r\n","<div class = 'note alert-denger'><p>Please select an image.</p></div>");
			return false;
		 }
		
		
		
		/* Create directory if not exists*/
		if(!is_dir($directory))
		{
			/* Make directory */
			mkdir("$directory/", 0777, true);
		}
		
		/* Get the each file access */
		$imgnames  = array();
		foreach($_FILES[$filename]["name"] as $key => $value)
		{
			/* get the name  of file*/
			$name = $_FILES[$filename]["name"][$key];
			
			/* Get the temporory file name */
			$tmp = $_FILES[$filename]["tmp_name"][$key];
			
			/* Get the random character */
			$randchar = chr(rand(97,122));
			
			/* get the random number */
			$number = rand(1,100000);
			$explodfname = explode('.', $name);
			$newfilename = round(microtime(true)) .$number.'.' . end($explodfname);
			
			
			/* Get new file */
			//$newfilename = $randchar.$name ;
			
			/* Use the method that everthing is  inserted in database */
			/* Then and then only upload the file */	
			
			$imgnames[] = $newfilename;
		}
		
		foreach($imgnames as $key=> $value )
		{
			move_uploaded_file($tmp, "$directory/$value");				
			//$collection->remove();		
			
		}
		
		
	}	
	
	/* Function using for error loging */
	public function ErrorConfiguration()
	{		
		ini_set('error_reporting', E_ALL);
		error_reporting(E_ALL);
		ini_set('log_errors',TRUE);
		ini_set('html_errors',FALSE);
		ini_set('error_log','../error/error_log.txt');
		ini_set('display_errors',FALSE);
		
	}
	
	/* Writing the document */
	public function WriteDocument()
	{		
		if($this->AcccepReqest() !== true)
		{
			return  printf("%s \r\n",$this->AcccepReqest());
		}
		
		/* Check if content is  more then one then */		
		$connection = $this->DBConfiguration();
		
		/* select databae */
		$db = $connection->kettyclothing;
		
		/* Select collection */
		$collection = $db->kettyclothingdb;		
		
		
		
		/* We are here on last time */
		/* Ge the all data */
		$productname 		= $this->productname; 
		$description 		= $this->discription ;
		$shortdiscription 	= $this->shortdiscription ;
		$availabledatefrom 	= $this->availabledatefrom ;
		$availabledateto 	= $this->availabledateto ;
		$sku 				= $this->sku ;
		$price 				= $this->price ;
		$taxclass 			= $this->taxclass ;
		$status 			= $this->status ;
		
		/* New document */
		$special_price			= $this->special_price;
		$available_items 		= $this->available_items;
		$origin 				= $this->origin;
		$lining 				= $this->lining;
		$material 				= $this->material;
		$condition				= $this->condition;
		$style 					= $this->style;
		$season 				= $this->season;
		$product_feature 		= $this->product_feature;
		$productLable 			= $this->productLable;
		$colors 				= $this->colors;
		$sizes 					= $this->sizes;
		$buttoncolor 			= $this->buttoncolor;
		$avaibility 			= $this->avaibility;
		$collectionu 			= $this->collectionu;
		$weight 				= $this->weight;
		
			
		$availabledatefrom = str_replace('/','-', $availabledatefrom);
		$availabledateto = str_replace('/','-', $availabledateto);
		/* Form a data in array for write the document */
		$data = array(
						"productname"=>	$productname,
						"description"=>	$description,
						"shortdiscription"=> $shortdiscription,
						"availabledatefrom"=>$availabledatefrom,
						"availabledateto"=>$availabledateto,
						"sku"=>$sku,
						"price"=>$price,
						"taxclass"=>$taxclass,
						"status"=>$status,
						"special_price" =>$special_price,
					"available_items" =>$available_items,
					"origin" =>$origin,
					"lining" =>$lining,
					"material" =>$material,
					"condition" =>$condition,
					"style" =>$style,
					"season" =>$season,
					"product_feature" =>$product_feature,
					"productLable" =>$productLable,
					"colors" =>$colors,
					"sizes" =>$sizes,
					"buttoncolor" =>$buttoncolor,
					"avaibility" =>$avaibility,
					"collectionu" =>$collectionu,
					"weight" =>$weight					
					);
		
		/* Check product sek if  match */
		$dsek =  $collection->findOne(array("sku"=>$sku));
		
		/*If sucess then return false  */
		if($dsek !== null)
		{
			/* Error */
			printf("%s \r\n",  "<div class = 'note alert-danger'>Product sek $sku already exist, can  not write document.</p></div>");
			return false;
		}
		
		$collection->insert($data);
		/* Close the connection */
		
		$connection->close();
				
		echo "1";
	}
	
	
	public function writefilenames()
	{
		
		sleep(2);
		/* Post */
		$sku = $_POST['sku'];		
		
		$directory = "../img/product-images/$sku/";
		
		
		
		if(is_dir($directory))
		{
			/* Scan the directory */
			$scan = scandir($directory);

			/* Remove the tow index */
			$rtu = array_splice($scan, 2);



			/* Get the connection */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
			$dcollection = $db->kettyclothingdb;
			
			$find1 = $dcollection->find();
			
		

			/* select databae */
			$collection = $db->product_images;
			
			
			/* Upload to the  server */
			$data = array("$sku"=>$rtu);

			$collection->insert($data);
			
			echo "<div class = 'note alert-success'><p>Product inserted sucessfully.</p></div>";
			
			return;
		}
		
		/* */
		echo "<div class = 'note note-danger'><p>Could not uplaod the product.</p></div>";
		return;	
		
	}
	
	
	public function writefilenamesToMysqli()
	{		
		ob_start();
		
		/* Sleep for two secound */
		sleep(2);
		
		/* Post Sku*/
		$sku = $_POST['sku'];
		
		/* Check directory */		
		$directory = "../img/product-images/$sku/";
		
		/* If directory exist */	
		if(is_dir($directory))
		{
			/* Scan the directory */
			$scan = scandir($directory);
			
			/* Remove the tow index */
			$rtu = array_splice($scan, 2);
			
			/* Get the connection */
			$mysqli = $this->MysqliSever1();
			
			/* Get the date */			
			$gdate = date('Y-m-d');
			
			/* Get the table name */			
			$tablename = 'product_images_7add4023a86ad';
			
			/* Escape the string with mysqli */		
			$sku = $mysqli->real_escape_string($sku);
			
			/* Encode the array as json */			
			$rtu = json_encode($rtu);
			
			/* Escape string */			
			$rtu = $mysqli->real_escape_string($rtu);
			
			/* Insert the date to mysqli server */			
			if(!$sql = $mysqli->query("INSERT INTO 
									$tablename(sku,image,date)VALUES
									('$sku','$rtu','$gdate')
									"))
			{
				/* Throw the error */
				printf("%s \r\n",$mysqli->error);
				
				/* Return false */
				return false;
			}
			
			/* Clean any html output */
			ob_clean();			
		}		
	}
	
	
	public function ReadProduct()
	{		
		/* Set the page */
		$page = 0;
		
		/* Set the limit */
		$limit = 10;
		
		/* If get page set get it */
		if(isset($_GET['page']))
		{
			/* Get the page */
			$page = $_GET['page'];
		}
		
		
		
		/* Skip page from */
		$skipfrom =  $page * $limit;
			
		$this->DBConfiguration();
			/* Set the template */
			$template = '<!-- This line will loop -->
							<div class = "col-md-3"> 
								<div class = "each-product">
								{#img}
								</div>
								
							<div class = "product-footer" style = "text-align:center;">
							<input type = "hidden" 
							<a href = "ecommerce_products_delete?id={@_id}&sku={@sku}"><button value = "Delete" class = "btn btn-sm btn-success filter-submit margin-bottom">Delete</button></a>
							<a href = "ecommerce_products_edit?id={@_id}&sku={@sku}"><button value = "Update" class = "btn btn-sm btn-success filter-submit margin-bottom">Update</button></a>
							</div>
							</div>						
					
						</div>											
					</div>  
                     ';
			/* Ge the connecton */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$dcollection = $db->kettyclothingdb;
		
			/* Find the all element */
			$find = $dcollection->find()->limit($limit)->skip($skipfrom);
			
			$collection = $db->product_images;
			
			/* Check if there is product */
			$coundrows = $dcollection->find()->count();
			
			/* If less then 1 return false  */
			if($coundrows < 1)
			{
				printf("%s \r\n","<div class = 'note note-danger'>
 	 				<p> NOTE: No product avaialbe to fetch.  </p>
					</div>");
				return false;
			}
		
			/* Get the colums names */
		
			$keys = '';
			foreach($find as $key => $value)
			{
				
				$keys = array_keys($value);
			}
			
			
			/* Extract the value that trying to get */
			
			/* Get everyhing between {}*/
			$reg = '/{@(.*?)}/';
	
			/* Set the matches */
			$matches = array();
			
			if(preg_match_all($reg, $template, $matches))
			{
				
			}
			
			/* Get the unique value */
			$uvalue = array_unique($matches[1]);
			
			$diffvalue = '';
			if(is_array($keys))
			{
				/* Find the different */
				$diffvalue = array_diff($uvalue, $keys);
			}
			
			
			if(is_array($diffvalue))
			{
				/* Cound must be 0 */
				if(count($diffvalue) > 0)
				{
				/* Error */
					printf("%s \r\n","Undefined indexs ".implode(',', $diffvalue)." in database.");
					return false;
				}
			}
		else{
			return false;
		}
			
		
			
			
			
			foreach($find as $key=> $value)
			{			
				$sku = $value["sku"];
				$this->data[] = $value;
				
			}
		
			/* Select the product images */
			$findimages = $collection->find()->limit($limit)->skip($skipfrom);
			
			
			foreach($findimages as $key=> $value)
			{
				foreach($value as $k => $v)
				{
					if($k == '_id')
					{
						continue;
					}

					$this->productimage[$k] = $v[0];
				}
				

			}
		
		
		$dir = '';
		/* If there is script filename */
		
			/* Directory should be one step back */
			$dir = "../img/product-images";
		
		
	
		
		foreach($this->productimage as $key => $value)
		{
			$link = $dir."/$key/$value";
			
			$this->productimage[$key] = $link;
			
		}
		
		$this->productimage = array_values($this->productimage);
		
		//return false;
		//echo "<pre>";
		for($i = 0; $i < count($this->data); $i++)
		{
			array_push($this->data[$i],$this->productimage[$i]);
		}
	}
	
	
	public function ReadProductforuser($limit)
	{		
		/* Set the page */
		$page = 0;
		
		/* Set the limit */
		//$limit = 10;
		
		/* If get page set get it */
		if(isset($_GET['page']))
		{
			/* Get the page */
			$page = $_GET['page'];
		}
		
		
		
		
		/* Skip page from */
		$skipfrom =  $page * $limit;
			
		$this->DBConfiguration();
			/* Set the template */
			$template = '<!-- This line will loop -->
							<div class = "col-md-3"> 
								<div class = "each-product">
								{#img}
								</div>
								
							<div class = "product-footer" style = "text-align:center;">
							
							<input type = "hidden" 
							<a href = "ecommerce_products_delete?id={@_id}&sku={@sku}"><button value = "Delete" class = "btn btn-sm btn-success filter-submit margin-bottom">Delete</button></a>
							<a href = "ecommerce_products_edit?id={@_id}&sku={@sku}"><button value = "Update" class = "btn btn-sm btn-success filter-submit margin-bottom">Update</button></a>
							</div>
							</div>						
					
						</div>											
					</div>  
                     ';
			/* Ge the connecton */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$dcollection = $db->kettyclothingdb;
			
			
				$rows = $dcollection->find()->count();
			
				// Get the random number between that 
				$rnd = rand(0, $rows);
		
				$skipfrom = $rnd;
			
				$find = $dcollection->find()->limit($limit)->skip($skipfrom);
		
				
			
			/* Find the all element */
			//$find = $dcollection->find()->limit($limit)->skip($skipfrom);
			
			$collection = $db->product_images;
			
			/* Check if there is product */
			$coundrows = $dcollection->find()->count();
			
			/* If less then 1 return false  */
			if($coundrows < 1)
			{
				printf("%s \r\n","<div class = 'note note-danger'>
 	 				<p> NOTE: No product avaialbe to fetch.  </p>
					</div>");
				return false;
			}
		
			/* Get the colums names */
		
			$keys = '';
			foreach($find as $key => $value)
			{
				
				$keys = array_keys($value);
			}
			
			
			/* Extract the value that trying to get */
			
			/* Get everyhing between {}*/
			$reg = '/{@(.*?)}/';
	
			/* Set the matches */
			$matches = array();
			
			if(preg_match_all($reg, $template, $matches))
			{
				
			}
			
			/* Get the unique value */
			$uvalue = array_unique($matches[1]);
			$diffvalue = '';
			if(is_array($keys))
			{
				/* Find the different */
				$diffvalue = array_diff($uvalue, $keys);
			}
			
			if(gettype($diffvalue) == 'string')
			{
				return false;
			}
			/* Cound must be 0 */
			if(count($diffvalue) > 0)
			{
				/* Error */
				printf("%s \r\n","Undefined indexs ".implode(',', $diffvalue)." in database.");
				return false;
			}
		
			
			
			
			foreach($find as $key=> $value)
			{			
				$sku = $value["sku"];
				$this->data[] = $value;
				
			}
		
			if(count($this->data) < 1)
			{
				return false;
			}
		
			/* Select the product images */
			$findimages = $collection->find()->limit($limit)->skip($skipfrom);
			
			
			foreach($findimages as $key=> $value)
			{
				foreach($value as $k => $v)
				{
					if($k == '_id')
					{
						continue;
					}

					$this->productimage[$k] = $v[0];
				}
				

			}
		
		
		$dir = '';
		/* If there is script filename */
		
			/* Directory should be one step back */
			$dir = "../img/product-images";
		
		
	
		
		foreach($this->productimage as $key => $value)
		{
			$link = $dir."/$key/$value";
			
			$this->productimage[$key] = $link;
			
		}
		
		$this->productimage = array_values($this->productimage);
		
		//return false;
		//echo "<pre>"		
		for($i = 0; $i < count($this->data); $i++)
		{
			array_push($this->data[$i],$this->productimage[$i]);
		}
	}
	
	public function AllReadProdcut()
	{
		/* Run fetch function */
		$this->ReadProduct();	
		

		$data = '<div class = "col-md-3"> 
					<div class = "each-product">
						<img src = "{{&0}}" />
					</div>

					<div class = "product-footer" style = "text-align:center;">
						
						<a href = "../ajax/ecommerce_products_delete?id={{&_id}}&sku={{&sku}}" onclick = "DeleteProduct(this);return false;"><button value = "Delete" class = "btn btn-sm btn-success filter-submit margin-bottom">Delete</button></a>
						<a href = "ecommerce_products_edit?id={{&_id}}&sku={{&sku}}"><button value = "Update" class = "btn btn-sm btn-success filter-submit margin-bottom">Update</button></a>
					</div>
				</div>';					

		
		$regrex = '/{{&(.*?)}}/';
		
	
		$div = '';
		$div .= '<div class = "col-md-12">';
		$div .= '<div class = "portlet light portlet-fit portlet-datatable bordered">';
		$div .= '<div class = "row">';
		$div .= '<div class = "product-edit">';
		$div .= $this->getTemplate($data, $this->data,$regrex);
		$div .= '</div>';
		$div .= '</div>';
		$div .= '</div>';
		$div .= '</div>';
		 
		return $div;
		
	}
	
	/* Writing method */
	public function getTemplate($template,$data,$regrex)
	{
		/* Check the template must be  string */
		if(gettype($template) !== 'string')
		{
			/* Error */
			printf("%s <br/>","Template data type must be string.");
			return false;
		}

		/* Data must be in array */
		if(!is_array($data))
		{
			/* Error */
			printf("%s <br/>","Data type must be in array.");
			return false;
		}

		/* Data type each value must be array */
		$arrval = array_values($data);

		/* Is all value is array */
		$IsAllValArray = true;
		/* Loop and check */
		for($i = 0; $i < count($arrval); $i++)
		{
			/* IF not array */
			if(!is_array($arrval[$i]))
			{
				$IsAllValArray = false;
				break;

			}
		}

		/* Check value is not false */
		if($IsAllValArray === false)
		{
			/* Error */
			printf("%s <br/>","Value is not array in index $i.");
			return false;
		}

		/* Get the everything in the {(&)} special signature */	

		/* Matches */
		$matches = array();

		/* Get matches */
		$getmatches = '';
		/* Check how many matches */
		if(preg_match_all($regrex,$template,$matches))
		{
			$getmatches = $matches[1];
		}
		
		$serverkeys = '';	
		$diffkey = '';
		
		sort($data);
		
		
		if(count($data)> 0)
		{
			/* get the array keys */
			$serverkeys = array_keys($data[0]);
			
			/* Must not use undefined index */
			$diffkey = array_diff($getmatches,$serverkeys);
		}	

		/* Check variable must be empty */
		if(!empty($diffkey))
		{
			/* Errr */
			$getindex = implode(',',$diffkey);

			/* Message */
			printf("%s <br/>","Undefined index $getindex.");
			return false;
		}

		 $result = '';

		/* Go and loop each data and replace with it's value */
		$returndata = '';
		$reg2 = '/{{@(.*?)}}/';
		
		
		/* Loop each data */
		for($j = 0; $j < count($data); $j++)
		{
			/* Get the template  */
			$output = $template;
			/* where indexs matches in template  */
			foreach($data[$j] as $key => $value)
			{
				$reg = str_replace('(.*?)',$key, $regrex);

				/* Check with regular expression */
				if(preg_match($reg,$template))
				{
					/* Replace with */
					$output = preg_replace($reg,$value,$output);
					

				}			
				
			//echo $ReplacedData ;	
			//echo "<br/>";
			}

			 $result .= $output;
		}

		
		return $result;

		}

	/* Delete the product */
	public function DeleteProduct($id, $sku)
	{
		/* Check if set */
		if(isset($_GET[$id]) && isset($_GET[$sku]))
		{
			/* get the id and sku */
			$id = htmlspecialchars($_GET[$id]);
			$sku = htmlspecialchars($_GET[$sku]);
			
			/* Get the connection */
			
			/* Check if content is  more then one then */		
			$connection = $this->DBConfiguration();
		
			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$collection = $db->kettyclothingdb;
			
			/* select databae */
			$collection1 = $db->product_images;
			
			/* Check that exist */
			
			/* Match the sek */
			$findsekid = $collection->find(array("sku"=>$sku,'_id' => new MongoId($id)))->count();
			
			 
			/* Must be 1*/
			if($findsekid != '1')
			{
				/* Throw eror */
				printf("%s \r\n","<div class  = 'note note-danger'><p>Product did not found with sku $sku and MongodId $id.</p></div>");
				return false;
				
			}
			
			/* Remove everything from direcotry */
			$dir = "../img/product-images/$sku";
			
			/* check directory */
			if(is_dir($dir))
			{
				/* Scan the directory */
				$productfile = glob("$dir/*");
				
				/* unlink each file */
				foreach($productfile as $value)
				{
					/* Un link*/
					unlink($value);
				}
			}
			
			/* remove the directory */
			rmdir($dir);
			/* Now delete the product from database */
			
			/* Find the value */
			$findimg = $collection1->find();
			
			$gii = '';
			/* Get the id by match */
			foreach($findimg as $key => $value)
			{
				
				foreach($value as $k => $v)
				{
					if($k == $sku)
					{
						$gii = $value['_id'];
					}
				}
				
			}			
			
			if($gii == new MongoId($gii))
			{
				$rmimg = $collection1->remove(array("_id"=> new MongoId($gii)));
			}
			
			
			/* Delete everything from main database */			
			$removefromdb = $collection->remove(array("sku"=>$sku,'_id' => new MongoId($id)));
						
			/* Close the connection */
			$connection->close();
			
			}
	}	
	
	/* Update product */
	public function UpdateProduct($id , $sku)
	{
		if(isset($_GET[$id]) && isset($_GET[$sku]))
			{
				/* get the id and sku */
				$id = htmlspecialchars($_GET[$id]);
				$sku = htmlspecialchars($_GET[$sku]);
				
				/* Check if content is  more then one then */		
				$connection = $this->DBConfiguration();

				/* select databae */
				$db = $connection->kettyclothing;

				/* Select collection */
				$collection = $db->kettyclothingdb;

				/* select databae */
				$collection1 = $db->product_images;

				$findsekid = $collection->find(array("sku"=>$sku,'_id' => new MongoId($id)))->count();


				/* Must be 1*/
				if($findsekid != '1')
				{
					/* Throw eror */
					printf("%s \r\n","<div class = 'note note-danger'><p>Product did not found with sku $sku and MongodId $id.</p></div>");
					return false;

				}

				/* Find the  product */				
				$find = $collection->find(array("sku"=>$sku,'_id' => new MongoId($id)));
			
				/* Loop the data */
				foreach($find as $key => $value)
				{
					$this->updatetodata[] = $value;
				}
				
			$this->sku = $sku;
			$this->id = $id;
			/* Close the connection */
			$connection->close();				
		}
	}
	
	/* Setting the html element for updating */
	public function SetHtmlObject($id, $sku)
	{
		/* Run the previsous method */
		$this->UpdateProduct($id , $sku);
		$data = $this->updatetodata;
		
		/* Get the regression */
		$reg = '/{{@(.*?)}}/';
		
		/* Get the template */
		$template = '<div class="form-body">
						<div class="form-group">
							<label class="col-md-2 control-label">Name:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<input type="text" id = "name"class="form-control" name="product[name]" placeholder="" value = "{{@productname}}"> </div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Description:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<textarea class="form-control"  id = "discription"name="product[description]">{{@description}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Short Description:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<textarea class="form-control" name="product[short_description]" id = "short-discription">{{@shortdiscription}}</textarea>
								<span class="help-block"> shown in product listing </span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Available Date:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy" >
									<input type="text" class="form-control" name="product[available_from]" id = "date-available-from" value = "{{@availabledatefrom}}">
									<span class="input-group-addon"> to </span>
									<input type="text" class="form-control" name="product[available_to]" id = "date-available-to" value = "{{@availabledateto}}"> </div>
								<span class="help-block"> availability daterange. </span>
							</div>
						</div>
						
							<input type="hidden" class="form-control" name="product[sku]" placeholder="" id = "sku" value = "'.$this->sku.'"> 
							<input type="hidden" class="form-control" name="product[sku]" placeholder="" id = "id" value = "'.$this->id.'"> 
							
							
                                                        
						<div class="form-group">
							<label class="col-md-2 control-label">Price:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<input type="text" class="form-control" name="product[price]" placeholder="" id = "price" value = "{{@price}}"> </div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tax Class:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<select class="table-group-action-input form-control input-medium" name="product[tax_class]" id = "tax-class">
									<option value="">Select...</option>
									<option value="1" selected>None</option>
									<option value="0">Taxable Goods</option>
									<option value="0">Shipping</option>
									<option value="0">USA</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Status:
								<span class="required"> * </span>
							</label>
							<div class="col-md-10">
								<select class="table-group-action-input form-control input-medium" name="product[status]" id = "status">
									<option value="">Select...</option>
									<option value="1">Published</option>
									<option value="0">Not Published</option>
								</select>
							</div>
						</div>

				<div class="form-group">
			 <label class="col-md-2 control-label">Upload Images</label>
				<div class="col-xs-4" >
					<input type="file"  name="option[]" />
				</div>
			<div class="col-xs-4">
			  <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
			</div>


			</div>
			 <div class="form-group hide" id="optionTemplate">
			 <label class="col-md-2 control-label"></label>
			  <div class="col-xs-4">
				  <input type="file" name="option[]" />
			</div>
			<div class="col-xs-4">
			  <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
		  </div>
			</div>			<!-- Here end-->
			 </div>
                                                ';
		
		echo $this->getTemplate($template,$data, $reg);
		
	}
	
	/* Writing function for select */
	public function updatingProduct($sku)
	{
		/* Set the method  */
		$this->AcccepReqest();		
		
		$productname = $this->productname;
		$description = $this->discription;
		$shortdiscription = $this->shortdiscription;
		$availabledatefrom = $this->availabledatefrom;
		$availabledateto = $this->availabledateto;
		$sku = $this->sku;
		$price = $this->price;
		$taxclass = $this->taxclass;
		$status= $this->status;	
		
		/* Check if content is  more then one then */		
		$connection = $this->DBConfiguration();

		/* select databae */
		$db = $connection->kettyclothing;

		/* Select collection */
		$collection = $db->kettyclothingdb;

		/* select databae */
		$collection1 = $db->product_images;
		
		/* Check product exsit */
		$checkproduct = $collection->find(array("sku"=>$sku))->count();
		
		
		if($checkproduct != '1')
		{
			/* Error */
			print_r("%s '\r\n","<div class = 'note note-danger'>
 	 	<p>Product with sku $sku not exists. Can not update the prodcut.</p>
		</div>");
		return false;
		}
		/*Update the data */
		$data = array(
						"productname"=>	$productname,
						"description"=>	$description,
						"shortdiscription"=> $shortdiscription,
						"availabledatefrom"=>$availabledatefrom,
						"availabledateto"=>$availabledateto,
						"price"=>$price,
						"taxclass"=>$taxclass,
						"status"=>$status						
					);
		
		$collection->update(array("sku"=>$sku),array('$set'=>$data));
		
		
		$connection->close();
		$directory = "../img/product-images/$sku";

		/* Using the method */
		
		
		if(is_dir($directory))
		{
			/* Scan the directory */
			$productfile = glob("$directory/*");

			/* unlink each file */
			foreach($productfile as $value)
			{
				/* Un link*/
				unlink($value);
			}
		}
		echo "1";
	
	}
	
	/* Update the images */
	public function updateImages()
	{
		$filename = 'option';
		/* get the sku */
		$sku = htmlspecialchars($_POST['sku']);

		/* Setting the directory */
		$directory = "../img/product-images/$sku";

		/* Using the method */
					
		$this->UploadFileOop($filename, $directory, $sku);
		
	}
	
	public function updateFileNames()
	{
		
		sleep(2);
		/* Post */
		$sku = $_POST['sku'];		
		
		$directory = "../img/product-images/$sku/";
		
		
		
		if(is_dir($directory))
		{
			/* Scan the directory */
			$scan = scandir($directory);

			/* Remove the tow index */
			$rtu = array_splice($scan, 2);



			/* Get the connection */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
			//$dcollection = $db->kettyclothingdb;
			
			//$find1 = $dcollection->find();
			
		

			/* select databae */
			$collection = $db->product_images;
			
			/* Remove old one */
			
			$findimg = $collection->find();
			
			$gii = '';
			/* Get the id by match */
			foreach($findimg as $key => $value)
			{
				
				foreach($value as $k => $v)
				{
					if($k == $sku)
					{
						$gii = $value['_id'];
					}
				}
			
			}
			if($gii == new MongoId($gii))
			{				

				$collection->update(array('_id'=> new MongoId($gii)), array('$set' => array("$sku"=>$rtu)));
			
				echo "<div class = 'note alert-success'><p>Product update sucessfully.</p></div>";
			
				return;
			}		
			
			/* Upload to the  server */
			
		}
	}
	
	public function pagination()
	{
		/* Ge the connecton */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$dcollection = $db->kettyclothingdb;
		
			/* Find the all element */
			$find = $dcollection->find();
			
			$collection = $db->product_images;
		
			/* count de collection */
			$countrec = $dcollection->find()->count();
			
			/* How many per page require */
			$ppr = 10;
			
			/* Create page */
			$tp = ceil($countrec/$ppr)-1;
			//echo $tp;
			
		
			$scriptfile = $_SERVER['SCRIPT_FILENAME'];
		
			$pagination = '<ul class="pagination">';
			for($j = 1; $j <= $tp; $j++)
			{
				if(strpos($scriptfile, 'administrator') || strpos($scriptfile, 'ajax'))
				{
					$pagination .= "<li><a href = '../ajax/pagination?page=$j' onclick = 'pagination(this);return false;'>$j</a></li>";
				}
				else
				{	
					$pagination .= "<li><a href = 'ajax/pagination?page=$j' onclick = 'pagination(this);return false;'>$j</a></li>";
				}
				
			}	
	
			
			$pagination .= '</ul>';
	
			return $pagination;
		
		/* Close connection */
		$connection->close();
	}
	
	
	public function fetchProductForUser($limit)
	{			
							 
			$this->ReadProductforuser($limit);
			$data = '<div class = "col-md-3"> 
					<div class = "each-product">
						<a href="http://localhost/newproject3/administrator_d03fb619b3f94efa8b/ecommerce_products_edit?id={{&_id}}&sku={{&sku}}" class="cbp-singlePage" rel="nofollow"><img src = "{{&0}}" /></a>
					</div>					
					<div class = "product-footer" style = "text-align:center;">						
											
					</div>
				</div>';					

		
		$regrex = '/{{&(.*?)}}/';
		
	$div = '';
		//$div = '<div style="height: 328px;" id="js-grid-juicy-projects" class="cbp cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">';
		
		$div .= $this->getTemplate($data, $this->data,$regrex);
		//$div .= '</div>';
		
		 
		return $div;
	}
		
	public function productDiscription($id, $sku)
	{
		/* Get the id */
		if(isset($_GET[$id]) && isset($_GET[$sku]))
		{
			/* get the id and sku */
			$id = htmlspecialchars($_GET[$id]);
			$sku = htmlspecialchars($_GET[$sku]);
			
			/* Get the connection */
			$dir = "img/product-images/$sku";
			
			$this->imgdir = $dir;
			/* Check if content is  more then one then */		
			$connection = $this->DBConfiguration();
		
			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$collection = $db->kettyclothingdb;
			
			/* select databae */
			$collection1 = $db->product_images;
			
			/* Check that exist */
			
			/* Match the sek */
			$findsekid = $collection->find(array("sku"=>$sku,'_id' => new MongoId($id)))->count();
			
			 
			/* Must be 1*/
			if($findsekid != '1')
			{
				/* Throw eror */
				printf("%s \r\n","<div class  = 'note note-danger'><p>Product did not found with sku $sku and MongodId $id.</p></div>");
				
				/* Close the connection*/
				$connection->close();
				return false;
				
			}
			
			/* Select product by id and sek */
			$find = $collection->find(array("_id"=>new MongoId($id),"sku"=>$sku));
			
			$price = '';
			/* If sucess get the details */
			foreach($find as $key => $value)
			{		
				$this->price = $value["price"];
			}
			
			
			$this->pbik = array($value);			
			
			$findimages = $collection1->find();
			/* Fetch the images */
			$imgaes = '';
			$gii = '';
			
			foreach($findimages as $key => $value)
			{
				
				foreach($value as $k => $v)
				{
					if($k == $sku)
					{
						$gii = $v;
					}
				}
				
			}		
			
			$this->productallimg = $gii;
		}
		
		$this->id = $id;
		$this->sku = $sku;
	}
	
	public function loopimages($dir, $arrayimg,$element , $reg)
	{	
		$element = '<li class="cbp-slider-item">
                	<a href="{{&0}}" class="cbp-lightbox">
                    <img src="{{&0}}" alt=""></a>
				</li>';
		
		$getcon  = '';
		/* Check images must be in array */
		if(is_array($arrayimg))
		{
			for($i = 0; $i < count($arrayimg); $i++)
			{
				$link = $dir."/$arrayimg[$i]";

				$getcon .= str_replace($reg,"$link", $element);
			}

		}
		
		return $getcon;
			
		}	
	
	public function productDiscription1($id, $sku)
	{
		$this->GetCurrency();
		
		//echo $this->currency;
		//echo $this->currencycode;
		//echo $this->currencyvalue;
		
		
		/* Default currency symbol */
		$currencysymbol = 'USD';
		
		/* Default currency  */
		
		$currency = '$';
		/* Run the  previouse function */
		$this->productDiscription($id, $sku);
		
		/* Get the parameter */
		$dir = $this->imgdir; 
		$images = $this->productallimg;
		$reg = '{{&0}}';
		$element = '<li class="cbp-slider-item">
                	<a href="{{&0}}" class="cbp-lightbox">
                    <img src="{{&0}}" alt=""></a>
				</li>';
		
		/* Html images need to append */
		$images = $this->loopimages($dir, $images, $element, $reg);
		
		
		
		$diselement = "<div class='portfolio-content'>
		
    <div class='cbp-l-project-title'>{{&productname}}</div>
    <div class='cbp-l-project-subtitle'>kettyclothing Pvt. Ltd.</div>
    <div class='cbp-slider'>
        $images;
        </ul>
    </div>
    <div class='cbp-l-project-container'>
        <div class='cbp-l-project-desc'>
            <div class='cbp-l-project-desc-title'>
                <span>Project Description</span>
            </div>
            <div class='cbp-l-project-desc-text'>{{&description}}</div>
        </div>
        <div class='cbp-l-project-details'>
            <div class='cbp-l-project-details-title'>
                <span>Project Details</span>
				
				
            </div>
			<div id = 'message'>Project Details</div>
            <ul class='cbp-l-project-details-list'>
                <li>
                    <strong>Name</strong>{{&productname}}</li>
                <li>
                    <strong>Date </strong>{{&availabledatefrom}}</li>
                
				<li>
                    <strong>Price</strong> $this->currencycode ".$this->price * $this->currencyvalue." $this->currency</li>
				<li>
				<strong>Quqantity</strong><input class='form-control1'id='eqty'  type='text' >
				<input type = 'hidden'id='price' value = '{{&price}}'>
				<input type = 'hidden'id='productname'  value = '{{&productname}}'></li>
				
            </ul>
            <a href='ajax/addtocart?id=".urlencode($this->id)."&sku=".urlencode($this->sku)."' target='_blank' class='cbp-l-project-details-visit btn red uppercase' onclick = 'addtocart(this);return false;'>Add To Cart</a>
        </div>
    </div>
     </div>";
		
		
	/** Use the method to replace the data */
		$regrex = "/{{&(.*?)}}/";
		$div = $this->getTemplate($diselement ,$this->pbik ,$regrex);
		return $div;
		
	}
	
	
	public function RelatedProduct()
	{
		
		/* Use the method */
		$this->ReadProductforuser(3);
		$div = '<li class="cbp-l-project-related-item">
                    <a href="assets/global/plugins/cubeportfolio/ajax/project1.php?id={{&_id}}&sku={{&sku}}" class="cbp-singlePage" rel="nofollow">
                        <img src="{{&0}}" alt=""></a>s
                        <div class="cbp-l-project-related-title">{{&productname}}</div>
                    </a>
                </li>
            ';
	$regrex = '/{{&(.*?)}}/';
	
	$data = $this->data;
	
	$div = $this->getTemplate($div, $this->data,$regrex);
	return $div;
	} 

	
	public function repeatString($string,$times)
	{
		$value = '';
		
		// Sizes $sizesinserver = array('xs','s','m','l','xl','xxl','xxxl');
		$sizesinserver = array('xs','s','m','l','xl','xxl','xxxl');
		
		$regrex = '/^#[\w\d]{6}$/';
		
		/* Switch the statement */
		if($times == 1)
		{
			return $string;
		}
		
		if($times > 1)
		{
			for($i = 0; $i < $times; $i++)
			{
				$value .= "$string&@,";
			}
		}
		
		return substr($value,0,-3);
	}
	
	public function Cart($id,$sku,$qty,$price,$productname,$userbtncolor,$regularprice,$color,$size,$primary_image)
	{
		session_start();
		//$this->GetCurrency();
		
		//echo $this->currency;
		//echo $this->currencycode;
		//echo $this->currencyvalue;
		/* Get the product key */
		if(isset($_POST[$id]) && isset($_POST[$sku]) && isset($_POST[$qty]))
		{
			$gid = htmlspecialchars($_POST[$id]);
			$gsku =	htmlspecialchars($_POST[$sku]);
			$qty =	htmlspecialchars($_POST[$qty]);
			$price = htmlspecialchars($_POST[$price]);
			$gproductname = htmlspecialchars($_POST[$productname]);
			$userbtncolor = htmlspecialchars($_POST[$userbtncolor]);
			$regularprice = htmlspecialchars($_POST[$regularprice]);
			$color = htmlspecialchars($_POST[$color]);
			$size = htmlspecialchars($_POST[$size]);
			$product_image = $_POST[$primary_image];
			$htmlcolorcode = $_POST['htmlcolorcode'];
			$htmlbtncolorcode = $_POST['htmlbtncolorcode'];
			$htmlsizescode = $_POST['htmlsizescode'];
			
			if($qty < 1 && $qty > 100)
			{
				printf("%s /r/n","Quentity must be between 1 to 100.");
				return false;
			}
			
			
			/* Set color in array */
			$colorarray  = $this->repeatString($color, $qty);
			
			/* Set size in array */
			$sizearray =  $this->repeatString($size, $qty);
			
			/* Set button color in array */
			$buttoncolorarray =   $this->repeatString($userbtncolor, $qty);
			
			/* For color, button color and size index value should be array */
			//echo $buttoncolorarray;
			//return false;
			if(!isset( $_SESSION[$this->CartName()]))
			{			
				$_SESSION[$this->CartName()] = array(0=>array("id"=>$gid,
															"productname"=>$gproductname,
															"sku"=>$gsku,
															"qty"=>$qty,
															"price"=>$price,
															"totalamount"=>$qty*$price,
															"userbtncolor"=>$buttoncolorarray,
															"regularprice"=>$regularprice,
															"color"=>$colorarray,
															"size"=>$sizearray,
															"discount"=>($regularprice - $price) * $qty,
															"product_image" =>$product_image,
															"htmlcolorcode"=>$htmlcolorcode,
															 "htmlbtncolorcode"=>$htmlbtncolorcode,
															"htmlsizescode"=>$htmlsizescode
															));
				
				return true;
			}
			
			$sameProduct = false;
			$i = 0;
		
			foreach($_SESSION[$this->CartName()] as $eachItem)
			{
				// Index will increase here by each 
				$i++;
				
				/* Here some variable will be changed */
				//$colorarray
				//$sizearray
				//$buttoncolorarray
				
				// list the each index and their value 
				while(list($key,$value) = each($eachItem))
				{
					// if there is in each item key and their value match
					if($key == "id" && $value == $gid)
					{					
									
					array_splice($_SESSION[$this->CartName()],$i-1,1,array(
			array(
					"id"=>$gid,
					"productname"=>$gproductname,
					"sku"=>$gsku,
					"qty"=>$eachItem["qty"]+$qty,
					"price"=>$price,
					"totalamount"=>$eachItem["totalamount"]+$qty*$price,
					"userbtncolor"=>$eachItem["userbtncolor"]."&@,".$buttoncolorarray,
					"regularprice"=>$regularprice,
					"color"=>$eachItem["color"]."&@,".$colorarray,
					"size"=>$eachItem["size"]."&@,".$sizearray,
					"discount"=>$eachItem['discount']+($regularprice - $price) * $qty,
					"product_image" => $product_image,
					"htmlcolorcode" => $htmlcolorcode,
					"htmlbtncolorcode"=>$htmlbtncolorcode,
					"htmlsizescode" => $htmlsizescode
					)
				)
			);			
						
						$sameProduct = true;					
					}
				}				
			}
			
			if($sameProduct == false)
			{
				// push then new index in the array 
				array_push($_SESSION[$this->CartName()],array(
																"id"=>$gid,
																"productname"=>$gproductname,
																"sku"=>$gsku,
																"qty"=>$qty,
																"price"=>$price,
																"totalamount"=>$qty*$price,
																"userbtncolor"=>$buttoncolorarray,
																"regularprice"=>$regularprice,
																"color"=>$colorarray,
																"size"=>$sizearray,
																"discount"=>($regularprice - $price)*$qty,
																"product_image" => $product_image,
																"htmlcolorcode" => $htmlcolorcode,
																"htmlbtncolorcode"=> $htmlbtncolorcode,
																"htmlsizescode"=>$htmlsizescode
																)
															);
			}		
		}	
		
		return true;
}
	
	public function GetCartSessionTotal()
	{
		session_start();
		$totalqty = '';
		$totalcart = '';
		$totaldiscount = '';
		/* Check if session set then */
		if(isset($_SESSION[$this->CartName()]))
		{			
			
			
			foreach( $_SESSION[$this->CartName()] as $eachItem)
			{
					
				/* Get total quentity */
				$totalqty += $eachItem["qty"];
				
				/* Get total amount */
				$totalcart += $eachItem["totalamount"];
				
				/* Total discount */
				$totaldiscount += $eachItem["discount"];
			}
			
			/* Set the data in array */
			$totalcalculation = [["totalqty"=>$totalqty,"totalcart"=>$totalcart,"totaldiscount"=>$totaldiscount]];
			
			return $totalcalculation;
		}
		
		return false;
	}
	
	public function CartByGetRequest($id,$sku,$qty,$price,$productname)
	{
		session_start();
		//$this->GetCurrency();
		
		//echo $this->currency;
		//echo $this->currencycode;
		//echo $this->currencyvalue;
		/* Get the product key */
		if(isset($_GET[$id]) && isset($_GET[$sku]) && isset($_GET[$qty]))
		{
			$gid = htmlspecialchars($_GET[$id]);
			$gsku =	htmlspecialchars($_GET[$sku]);
			$qty =	htmlspecialchars($_GET[$qty]);
			$price = htmlspecialchars($_GET[$price]);
			$gproductname = htmlspecialchars($_GET[$productname]);
			
			if(!isset( $_SESSION["cart"]))
			{
				
				$_SESSION["cart"] = array(0=>array("id"=>$gid,"productname"=>$gproductname,"sku"=>$gsku,"qty"=>$qty,"price"=>$price,"totalamount"=>$qty*$price));
				return false;
			}
			
			$sameProduct = false;
			$i = 0;
		
			foreach($_SESSION["cart"] as $eachItem)
			{
				// Index will increase here by each 
				$i++;
				
				// list the each index and their value 
				while(list($key,$value) = each($eachItem))
				{
					// if there is in each item key and their value match
					if($key == "id" && $value == $gid)
					{
						
				array_splice($_SESSION["cart"],$i-1,1,array(array("id"=>$gid,
				"productname"=>$gproductname,
				"sku"=>$gsku,
				"qty"=>$eachItem["qty"]+$qty,
				"price"=>$price,"totalamount"=>$eachItem["totalamount"]+$qty*$price)));			
						
						$sameProduct = true;				
					}
				}				
			}
			
			if($sameProduct == false)
			{
				// push then new index in the array 
				array_push($_SESSION["cart"],array("id"=>$gid,"productname"=>$gproductname,"sku"=>$gsku,"qty"=>$qty,"price"=>$price,"totalamount"=>$qty*$price));
			}		
		}
	}
	
	
	/*Start the session */
	public function sessStart()
	{
		return session_start();
	}
		
	public function GetTotal($sessionname)
	{
		$this->GetCurrency();
		
		//echo $this->currency;
		//echo $this->currencycode;
		//echo $this->currencyvalue;
		
		/* Only run the script if session set */
		if(isset($_SESSION[$sessionname]) && count($_SESSION[$sessionname]) > 0)
		{	
		
		/* start to fetch the data */
		$this->cartsessiondata = 	$_SESSION[$sessionname];
		
		for($j = 0; $j < count($this->cartsessiondata); $j++)
		{
			$this->cartsessiondata[$j]["price"] = $this->cartsessiondata[$j]["price"] * $this->currencyvalue;
			$this->cartsessiondata[$j]["totalamount"] = $this->cartsessiondata[$j]["totalamount"] * $this->currencyvalue;
		}

		//echo "<pre>";
		//print_r($this->cartsessiondata);
		//echo "</pre>";
		
		$totalItems = '';
		$totalamount = '';
		foreach($this->cartsessiondata as $eachItems)
		{
			$totalItems += $eachItems["qty"];
			$totalamount += $eachItems["totalamount"];		
			
		}
		
		
		$this->totalItems = $totalItems;
		$this->totalamount = $totalamount;
		// setting html element 
		
		
		$htmlelement = '<tr class = "table-row" id = {{&id}}><td class="highlight">
									<div class="success"></div>
									<a href="assets/global/plugins/cubeportfolio/ajax/project1.php?id={{&id}}&sku={{&sku}}" class="cbp-singlePage" rel="nofollow">{{&productname}}</a>
								</td>                                                    
								<td class="hidden-xs">
									<div class = "col-md-6">{{&qty}}</div>
									<div class = "col-md-6">
									<input type = "text"  id = "eqty" size = "2" style = "border:1px solid #000000;">
								</div>
								</td>

								<td>'.$this->currencycode.'{{&price}} '.$this->currency.' </td>
								<td>
									<a href="ajax/addproduct.php?id={{&id}}&sku={{&sku}}" class="btn btn-outline btn-circle btn-sm green" onclick = "UpadateProduct(this); return false;">
										<i class="fa fa-plus"></i> Add</a>
									<a href="ajax/deleteproduct.php?id={{&id}}&sku={{&sku}}" class="btn btn-outline btn-circle btn-sm red" onclick = "DeleteProduct(this); return false;">
										<i class="fa fa-minus"></i> Delete </a>
								</td>
								<td> '.$this->currencycode.'{{&totalamount}} '.$this->currency.'</td>      
							   </tr>';
		
		$regrex = '/{{&(.*?)}}/';
			
		$table = '<thead>
					<tr>												
						<th>
							<i class="fa bold"></i> Name</th>
						<th class="hidden-xs">
							<i class="fa"></i> Qty </th>
						<th>
							<i class="fa"></i>Unite Price</th>
						<th>
							<i class="fa"></i>Customige</th>												
						<th>
							<i class="fa"></i> Total </th>
						<th> 
					</th>
					</tr>
				</thead>';
		
		$divsec = '<thead>
						<tr>
							<th>Total</th>
							<th>'.$this->totalItems.'</th>
							<th></th>
							<th></th>
							<th>'.$this->currencycode.''.$this->totalamount.' '.$this->currency.'</th>

						</tr>
					</thead>
				
					';
		
		$template  = $this->getTemplate($htmlelement,$this->cartsessiondata,$regrex);
		
		$html = '';
		$html .= $table;
		$html .= $template;
		$html .= $divsec;
		
		
		return $html;
			
		}	
		
	}
	
	
	public function GetTotal1($sessionname)
	{
		/* This will run after getting request from paypal */
		$this->GetCurrency();
		
		/* Only run the script if session set */
		if(isset($_SESSION[$sessionname]) && count($_SESSION[$sessionname]) > 0)
		{	
		
		/* start to fetch the data */
		$this->cartsessiondata = 	$_SESSION[$sessionname];
		
		for($j = 0; $j < count($this->cartsessiondata); $j++)
		{
			$this->cartsessiondata[$j]["price"] = $this->cartsessiondata[$j]["price"] * $this->currencyvalue;
			$this->cartsessiondata[$j]["totalamount"] = $this->cartsessiondata[$j]["totalamount"] * $this->currencyvalue;
		}

		//echo "<pre>";
		//print_r($this->cartsessiondata);
		//echo "</pre>";
		
		$totalItems = '';
		$totalamount = '';
		foreach($this->cartsessiondata as $eachItems)
		{
			$totalItems += $eachItems["qty"];
			$totalamount += $eachItems["totalamount"];		
			
		}
		
		
		$this->totalItems = $totalItems;
		$this->totalamount = $totalamount;
		// setting html element 
		
		$htmlelement = '<tr>
					<td class="t-body">{{&productname}}</td>                                                    
					<td class="t-body">{{&qty}}</td>
					<td class="t-body">'.$this->currencycode.'{{&price}} '.$this->currency.'</td>
					<td class="t-body"> '.$this->currencycode.'{{&totalamount}} '.$this->currency.'</td>      
				</tr>';
		$regrex = '/{{&(.*?)}}/';
			
		$table = '<thead>
					<tr>												
						<th class="t-body"> Name</th>
						<th class="t-body">Qty </th>
						<th class="t-body">Unite Price</th>																	
						<th class="t-body">Total </th>					
					</tr>
				</thead>';
		
		$divsec = '<thead class = "table-body">
						<tr>
							<th class="t-body">Total</th>
							<th class="t-body">'.$this->totalItems.'</th>
							<th class="t-body"></th>
							<th class="t-body">'.$this->currencycode.''.$this->totalamount.' '.$this->currency.'</th>

						</tr>
					</thead>
				
					';
		
		$template  = $this->getTemplate($htmlelement,$this->cartsessiondata,$regrex);
		
		$html = '';
		$html .= $table;
		$html .= $template;
		$html .= $divsec;
		
		
		return $html;
			
		}	
		
	}
	
	
	public function PayPalAPI($sessionname)
	{
		$this->GetCurrency();		
		
		if(isset($_SESSION[$sessionname]) && count($_SESSION[$sessionname]) > 0)
		{
		
		$pp_checkout_btn = "";
			
		/* Check that session user session set or not */
		$ifuserlogin = $this->isLogIn();
		
		$ifuserlogin = true;
		
		if($ifuserlogin !== true)
		{
			$pp_checkout_btn = '<a href = "login" class="btn btn-success"><i class = "fa fa-sign-in"></i> Login</a>';

			return $pp_checkout_btn;
		}
		
		
		$pp_checkout_btn .= '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
							<input type = "hidden" name = "cmd" value ="_cart" />
							<input type = "hidden" name = "upload" value = "1" />
							<input type = "hidden" name = "business" value = "'.$this->PaypalEmail.'" />';
		$i = 0;
		
		/*Each itmes details */
		foreach($_SESSION[$this->CartName()] as $eachItem)
		{
			$x = $i + 1;
			
			$productName = $eachItem["productname"];
			$productPrice = $eachItem["price"];
			$Quentity = $eachItem["qty"];
			
			$pp_checkout_btn .= '<input type="hidden" name="item_name_'.$x.'" value="'.$productName.'" />								
					<input type="hidden" name="amount_'.$x.'" value="'.$productPrice*$this->currencyvalue.'" />
					<input type="hidden" name="quantity_'.$x.'" value="'.$Quentity.'" />';
		
		
			$i++;
		}
		$pp_checkout_btn .= '<input type="hidden" name="notify_url" value= "'.$this->p_notify_url.'" />
							<input type="hidden" name="return" value= "'.$this->p_return.'" />
							<input type="hidden" name="rm" value="2" />
							<input type="hidden" name="cbt" value= "'.$this->p_cbt.'" />
							<input type="hidden" name="cancel_return" value= "'.$this->p_cancel_return.'" />
							<input type="hidden" name="Ic" value= "'.$this->p_Ic.'" />
							<input type="hidden" name="currency_code" value="'.$this->currency.'" />
								<!--
								<input type="image"  value="XXX" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make Payments with Paypal" />
								<button type="button" title="Continue Shopping" class="button btn-continue"><span><span>Continue Shopping</span></span></button>
								-->
								<button  value="XXX"  name="submit" alt="Make Payments with Paypal" class = "button btn-continue" /><span><span><i class = "fa fa-paypal"></i> Paypal</span></span></button>
			</form>';
	
	return $pp_checkout_btn;
		
			
		}
		
	}
	
	public function currency($from_Currency, $to_Currency, $amount)
	{
    
	$url = "https://www.google.com/finance/converter?a=" . $amount . "&from=" . $from_Currency . "&to=" . $to_Currency;

    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $rawdata = curl_exec($ch);
   
	curl_close($ch);

    preg_match_all("|<span class=bld>(.*)</span>|U", $rawdata, $matches);

    $result = explode(" ", $matches[1][0]);
	
	/* Currency value */
	$con_val = round($result[0], 2);
	
	/* Get the currency symbole */
	$cursym = $this->currencyCode();
		
	/* Currency format */
	$format = $cursym[$to_Currency]['format'];
	/* Set the cookie currency value */
	setcookie('ccv56598954',$con_val ,time() +3600, '/', $_SERVER['SERVER_NAME'] );
	
	/* Set currency to currency */	
	setcookie('ucc34433443',$to_Currency, time() + 3600, '/', $_SERVER['SERVER_NAME'] );
    
	/* Set cookie currency format as well */
	setcookie('cf34433443',$format, time() + 3600, '/', $_SERVER['SERVER_NAME'] );

	/* Round the integer */	
	/* Round the integer */	
	return round($result[0], 2);
	}
	
	/* Getting currency conversion */
	public function currencyCode()
	{
		$currencies = array(
					'AUD' => array(
					'label' => 'Australian Dollar',
					'format' => '$',
					),
					'CAD' => array(
					'label' => 'Canadian Dollar',
					'format' => '$',
					),
					'EUR' => array(
						'label' => 'Euro',
						'format' => '€',
					),
					'GBP' => array(
						'label' => 'Pound Sterling',
						'format' => '£',
					),
					'JPY' => array(
						'label' => 'Japanese Yen',
						'format' => '¥',
					),
					'USD' => array(
						'label' => 'U.S. Dollar',
						'format' => '$',
					),
					'NZD' => array(
						'label' => 'N.Z. Dollar',
						'format' => '$',
					),
					'CHF' => array(
						'label' => 'Swiss Franc',
						'format' => 'Fr',
					),
					'HKD' => array(
						'label' => 'Hong Kong Dollar',
						'format' => '$ ',
					),
					'SGD' => array(
						'label' => 'Singapore Dollar',
						'format' => '$',
					),
					'SEK' => array(
						'label' => 'Swedish Krona',
						'format' => 'kr',
					),
					'DKK' => array(
						'label' => 'Danish Krone',
						'format' => 'kr',
					),
					'PLN' => array(
						'label' => 'Polish Zloty',
						'format' => 'zł',
					),
					'NOK' => array(
						'label' => 'Norwegian Krone',
						'format' => 'kr',
					),
					'HUF' => array(
						'label' => 'Hungarian Forint',
						'format' => 'Ft',
					),
					'CZK' => array(
						'label' => 'Czech Koruna',
						'format' => 'Kč',
					),
					'ILS' => array(
						'label' => 'Israeli New Sheqel',
						'format' => '₪',
					),
					'MXN' => array(
						'label' => 'Mexican Peso',
						'format' => '$',
					),
					'BRL' => array(
						'label' => 'Brazilian Real',
						'format' => 'R$ ',
					),
					'MYR' => array(
						'label' => 'Malaysian Ringgit',
						'format' => 'RM ',
					),
					'PHP' => array(
						'label' => 'Philippine Peso',
						'format' => '₱ ',
					),
					'TWD' => array(
						'label' => 'New Taiwan Dollar',
						'format' => 'NT$ ',
					),
					'THB' => array(
						'label' => 'Thai Baht',
						'format' => '฿ ',
					),
					'TRY' => array(
						'label' => 'Turkish Lira',
						'format' => 'TRY ', // Unicode is ₺ but this doesn't seem to be widely supported yet (introduced Sep 2012)
					),
					'RUB' => array(
							'label'=>'Russian Rubel',
							'format'=>'руб'
					),
			);
	
	
		return $currencies;
	}
	

	/* Setting */
	public function settingcurrency($currency,$currencycode,$currencyvalue)
	{
		if(isset($_COOKIE[$currency]) && isset($_COOKIE[$currencycode]) && isset($_COOKIE[$currencyvalue]))
		{
			$this->currency = $_COOKIE[$currency];
			$this->currencycode = $_COOKIE[$currencycode];
			$this->currencyvalue = $_COOKIE[$currencyvalue];

			
		}
		else
		{
			$this->currency = 'USD';
			$this->currencycode = '$';
			$this->currencyvalue = '1';
		}
	}
	
	/* Get the cookie value */
	public function GetCurrency()
	{
		$this->settingcurrency('ucc34433443','cf34433443', 'ccv56598954');
	}	
	
	/* Check session status */
	public function SessionSatus()
	{
		if(session_status() == 'PHP_SESSION_ACTIVE ')
		{	
			return true;
		}
		
		return session_start();
		
	}
	
	public function PaypalCheckOutComplete()
	{	
		
		/* If we receive the payment then we will receive index with value */
		if(isset($_SERVER['HTTP_REFERER']))
		{
			/* Check word paypal must exists */
			if(strpos($_SERVER['HTTP_REFERER'], 'paypal'))
			{
				
				/* Simply insert the value to d**/
				/* Check the index set */
				if(isset($_POST['address_status']))
				{
					/* Get the connection */		
					$connection = $this->DBConfiguration();
		
					/* select databae */
					$db = $connection->kettyclothing;
				
					/* Select collection */
					$collection = $db->papal_checkout_complete;
					
					/*
						In general we are in development thfore 
						We are not checking ipn index and it's 
						Generate unique ipn each time so 
						before go to insert to database we need to check 
						$find = $collectino->find(array("ipn"=>$_POST["ipn"]))->count();
						
						if($find != null)
						{	
							printf("%s \r\n","Payment details could not insert to database.");
							$connection->close();
							return false;
						}
						
					*/
					
					/* Fill date in mysql formate */
					$_POST['payment_date'] = date('Y-m-d H:i:s', strtotime($_POST['payment_date']));
					
					$collection->insert($_POST);
					
					$first_name = $_POST['first_name'];
					$last_name = $_POST['last_name'];
					$receiver_email = $_POST['receiver_email'];
					$payment_date = $_POST['payment_date'];
					$payer_email = $_POST['payer_email'];
					$address_country = $_POST['address_country'];
					$address_city = $_POST['address_city'];
					$address_street = $_POST['address_street'];
					$address_zip = $_POST['address_zip'];
					$address_state = $_POST['address_state'];
					$payment_date = $_POST['payment_date'];
					
					/* Get the buffering content */
					$gbc = ob_get_contents();
					
					/* Starting cleaning */
					ob_end_clean();
					
					setcookie('first_nameccf5fc0187',$first_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('last_nameccf5fc018737',$last_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('payer_emailccf5fc0187',$payer_email ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('address_countryccf5fc0187',$address_country,time() +3600, '/',$_SERVER['SERVER_NAME']);
					setcookie('address_cityccf5fc0187',$address_city ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('address_streetccf5fc0187',$address_street ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('address_zipccf5fc0187',$address_zip ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('address_stateccf5fc0187',$address_state ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					setcookie('payment_dateccf5fc0187',$payment_date ,time() +3600, '/', $_SERVER['SERVER_NAME']);
					
					/* Eacho previous content */
					
					echo $gbc;
					
					echo '<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Success!</strong> Thank you for you payment, we are in the process for delivery.
			<a href = "demo.php"><i class = "fa fa-download" style = "cursor:pointer;">  Download Invoice</i>			</a>
		</div>  
	
					';
					
					/* Close the connection */
					$connection->close();
					
					/* Insering in mysqli tables as well */
					
					/* Required the for connection */
					require_once 'mysqli-server.php';
		
					/* Get mysqli connection */
					$obj = new MySQLServer();
					
					/* Table name */
					
					$table_prifix = '7add4023a86ad';
				
					/* Setting table name */
					$tablename = "papal_checkout_complete_$table_prifix";
					/* Run the object to insert */
					
					$obj->PaypalCheckOutCompleteMysqli($tablename, $_POST);
					
				}
				
			}
		}
		
	}
	

	/* Inserting data in mysqli table */
	public function PaypalCheckCompleteMysqli()
	{
		
		/* Get the connection */
		$mysqli= $this->MysqliSever1();
		
		/* Table name */
		$tablename = 'papal_checkout_complete_7add4023a86ad';
		
		
		/* Close the connetion */
		$mysqli->close();
	}
	
	/* Sending Invoice to the customers */
	public function SendInvoice($email)
	{
		if(isset($_SERVER['HTTP_REFERER']))
		{
			/* Check word paypal must exists */
			if(strpos($_SERVER['HTTP_REFERER'], 'paypal'))
			{
				/* if set payper email address index */
				if(isset($_POST[$email]))
				{
					/* Set every details to for pdf file */
					
				}
			}
		}
		
		/* We need php mailer class to send the email with attachment */
		
	}
	
	/* Check if paypal request */
	
	public function IfPaypalRequest()
	{
		if(isset($_SERVER['HTTP_REFERER']))
		{
			/* Check word paypal must exists */
			if(strpos($_SERVER['HTTP_REFERER'], 'paypal'))
			{
				
				/* Simply insert the value to d**/
				/* Check the index set */
				if(isset($_POST['address_status']))
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function CreatePDF()
	{
		$setobj = $this->sessStart();
		
		require_once("dompdf_config.inc.php");
		
		//$get = $obj-> GetTotalForPdfFile('cart');
		$setobj = $this->GetTotal1($this->CartName());
		
		/* Payper information by cookie 
		setcookie('first_nameccf5fc0187',$first_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('last_nameccf5fc018737',$last_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('payer_emailccf5fc0187',$payer_email ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_countryccf5fc0187',$address_country,time() +3600, '/',$_SERVER['SERVER_NAME']);
		setcookie('address_cityccf5fc0187',$address_city ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_streetccf5fc0187',$address_street ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_zipccf5fc0187',$address_zip ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_stateccf5fc0187',$address_state ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		*/
		
		$first_name = $_COOKIE['first_nameccf5fc0187'];
		$last_name = $_COOKIE['last_nameccf5fc018737'];
		$payer_email = $_COOKIE['payer_emailccf5fc0187'];
		$address_country = $_COOKIE['address_countryccf5fc0187'];
		$address_city = $_COOKIE['address_cityccf5fc0187'];
		$address_street = $_COOKIE['address_streetccf5fc0187'];
		$address_zip = $_COOKIE['address_zipccf5fc0187'];
		$address_state = $_COOKIE['address_stateccf5fc0187'];
		$payment_date = $_COOKIE['payment_dateccf5fc0187']; 
		

		/* Full address */
		$fulladdress = $address_street.' '.$address_city.' '.$address_state.' '.$address_country.' '.$address_zip;
		
		$contnet = '<!DOCTYPE html>
					<html lang="en">
					<!--<![endif]-->
					<!-- BEGIN HEAD -->

   					 <head>
					<meta charset="utf-8" />
					<title>Metronic | Invoice 2</title>
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta content="width=device-width, initial-scale=1" name="viewport" />
					<meta content="" name="description" />
					<meta content="" name="author" />
					 <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
					<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
					<link href="css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
					<link href="css/new.css" rel="stylesheet" type="text/css" />
					<link href="css/uniform.default.css" rel="stylesheet" type="text/css" />
					<link href="css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

					<link href="css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
					<link href="css/plugins.min.css" rel="stylesheet" type="text/css" />

					<!-- BEGIN THEME LAYOUT STYLES -->
					<link href="css/layout.min.css" rel="stylesheet" type="text/css" />
					<link href="css/custom.min.css" rel="stylesheet" type="text/css" />
					<link href="css/light.min.css" rel="stylesheet" type="text/css" />		
					<link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />		
					<link href="css/invoice-2.min.css" rel="stylesheet" type="text/css" />
					<link rel="shortcut icon" href="favicon.ico" /> </head>
				<!-- END HEAD -->
					<body>

					<div class = "row">
					<div class  = "col-md-4"></div>

					<div class  = "col-md-4">
					<div class="invoice-logo">
					  <img src="assets/global/img/logo.png" alt="logo" class="logo-default">
						<h3 class="uppercase">Invoice</h3>
					</div>
					</div>


					<div class  = "col-md-4 cf-size">	
						<span class="bold uppercase">kettyclothing Pvt. Ltd.</span>
						<br/> 25, Lorem Lis Street, Orange C
						<br/> California, US

						<span class="bold">T</span> 1800 123 456
						<br/>
						<span class="bold">E</span> support@kettyclothing.com
						<br/>
						<span class="bold">W</span> www.keenthemes.com 
					</div>

					</div>
					<div class = "row invoice-cust-add">
						<div class = "col-md-3">

							<h5 class="invoice-title uppercase">Customer</h5>
							<p class="invoice-desc">'.$first_name.' '.$last_name.'</p>
						</div>


						<div class = "col-md-3">
						<h5 class="invoice-title uppercase">Date</h5>
							<p class="invoice-desc">'.$payment_date.'</p>
						</div>

						<div class = "col-md-6">
						<h5 class="invoice-title uppercase">Address Address</h5>
							<p class="invoice-desc inv-address">'.$fulladdress.'</p>
						</div>

					</div>
					<div class="page-container">
							<div class="page-content">
								<div class="page-sidebar-wrapper">
								<div class = "row">	
									<div class="col-md-12">
												<!-- BEGIN SAMPLE TABLE PORTLET-->
												<div class="portlet">
													<div class="portlet-title">
													 </div>
													<div class="portlet-body">
														<div class="table-scrollable">
															<table class="table table-striped table-bordered table-advance table-hover">          
														'.$setobj.'													
															</table>
														</div>
													</div>
												</div>
												<!-- END SAMPLE TABLE PORTLET-->
											</div>
							<div class="col-md-6"></div>
						</div> 

								</div>
							</div>
							</div>


					<div class = "row">
					<h3>Disclamer</h3>
					<p style = "font-size:10px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, cumque, earum blanditiis incidunt minus commodi consequatur provident quae. Nihil, alias, vel consequatur ab aliquam aspernatur optio harum facilis excepturi mollitia autem voluptas cum ex veniam numquam quia repudiandae in iure. Assumenda, vel provident molestiae perferendis officia commodi asperiores earum sapiente inventore quam deleniti mollitia consequatur expedita quaerat natus praesentium beatae aut ipsa non ex ullam atque suscipit ut dignissimos magnam!</p>

					</div>
				</body>
		</html>';		

	// We check wether the user is accessing the demo locally
	$local = array("::1", "localhost");
	$is_local = in_array($_SERVER['REMOTE_ADDR'], $local);
   $dompdf = new DOMPDF();
  //$dompdf->load_html_file('<p></p>');
   $dompdf->load_html($contnet);
   $dompdf->set_paper('a4','portrait');
   $dompdf->render();
	
	/* If you want password protected */
	//$dompdf->get_canvas()->get_cpdf()->setEncryption("pass", "pass");
	
	/* */
   $dompdf->stream("invoice.pdf", array("Attachment" =>true)); 
	
	}
	
	public function CreatePDFAndSentAttachment()
	{
		$setobj = $this->sessStart();
		
		require_once("dompdf_config.inc.php");
		
		//$get = $obj-> GetTotalForPdfFile('cart');
		$setobj = $this->GetTotal1($this->CartName());
		
		/* Payper information by cookie 
		setcookie('first_nameccf5fc0187',$first_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('last_nameccf5fc018737',$last_name ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('payer_emailccf5fc0187',$payer_email ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_countryccf5fc0187',$address_country,time() +3600, '/',$_SERVER['SERVER_NAME']);
		setcookie('address_cityccf5fc0187',$address_city ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_streetccf5fc0187',$address_street ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_zipccf5fc0187',$address_zip ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		setcookie('address_stateccf5fc0187',$address_state ,time() +3600, '/', $_SERVER['SERVER_NAME']);
		*/
		
		$first_name = $_COOKIE['first_nameccf5fc0187'];
		$last_name = $_COOKIE['last_nameccf5fc018737'];
		$payer_email = $_COOKIE['payer_emailccf5fc0187'];
		$address_country = $_COOKIE['address_countryccf5fc0187'];
		$address_city = $_COOKIE['address_cityccf5fc0187'];
		$address_street = $_COOKIE['address_streetccf5fc0187'];
		$address_zip = $_COOKIE['address_zipccf5fc0187'];
		$address_state = $_COOKIE['address_stateccf5fc0187'];
		$payment_date = $_COOKIE['payment_dateccf5fc0187']; 
		

		/* Full address */
		$fulladdress = $address_street.' '.$address_city.' '.$address_state.' '.$address_country.' '.$address_zip;
		
		$contnet = '<!DOCTYPE html>
					<html lang="en">
					<!--<![endif]-->
					<!-- BEGIN HEAD -->

   					 <head>
					<meta charset="utf-8" />
					<title>Metronic | Invoice 2</title>
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta content="width=device-width, initial-scale=1" name="viewport" />
					<meta content="" name="description" />
					<meta content="" name="author" />
					 <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
					<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
					<link href="css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
					<link href="css/new.css" rel="stylesheet" type="text/css" />
					<link href="css/uniform.default.css" rel="stylesheet" type="text/css" />
					<link href="css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

					<link href="css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
					<link href="css/plugins.min.css" rel="stylesheet" type="text/css" />

					<!-- BEGIN THEME LAYOUT STYLES -->
					<link href="css/layout.min.css" rel="stylesheet" type="text/css" />
					<link href="css/custom.min.css" rel="stylesheet" type="text/css" />
					<link href="css/light.min.css" rel="stylesheet" type="text/css" />		
					<link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />		
					<link href="css/invoice-2.min.css" rel="stylesheet" type="text/css" />
					<link rel="shortcut icon" href="favicon.ico" /> </head>
				<!-- END HEAD -->
					<body>

					<div class = "row">
					<div class  = "col-md-4"></div>

					<div class  = "col-md-4">
					<div class="invoice-logo">
					  <img src="assets/global/img/logo.png" alt="logo" class="logo-default">
						<h3 class="uppercase">Invoice</h3>
					</div>
					</div>


					<div class  = "col-md-4 cf-size">	
						<span class="bold uppercase">kettyclothing Pvt. Ltd.</span>
						<br/> 25, Lorem Lis Street, Orange C
						<br/> California, US

						<span class="bold">T</span> 1800 123 456
						<br/>
						<span class="bold">E</span> support@kettyclothing.com
						<br/>
						<span class="bold">W</span> www.keenthemes.com 
					</div>

					</div>
					<div class = "row invoice-cust-add">
						<div class = "col-md-3">

							<h5 class="invoice-title uppercase">Customer</h5>
							<p class="invoice-desc">'.$first_name.' '.$last_name.'</p>
						</div>


						<div class = "col-md-3">
						<h5 class="invoice-title uppercase">Date</h5>
							<p class="invoice-desc">'.$payment_date.'</p>
						</div>

						<div class = "col-md-6">
						<h5 class="invoice-title uppercase">Address Address</h5>
							<p class="invoice-desc inv-address">'.$fulladdress.'</p>
						</div>

					</div>
					<div class="page-container">
							<div class="page-content">
								<div class="page-sidebar-wrapper">
								<div class = "row">	
									<div class="col-md-12">
												<!-- BEGIN SAMPLE TABLE PORTLET-->
												<div class="portlet">
													<div class="portlet-title">
													 </div>
													<div class="portlet-body">
														<div class="table-scrollable">
															<table class="table table-striped table-bordered table-advance table-hover">          
														'.$setobj.'													
															</table>
														</div>
													</div>
												</div>
												<!-- END SAMPLE TABLE PORTLET-->
											</div>
							<div class="col-md-6"></div>
						</div> 

								</div>
							</div>
							</div>


					<div class = "row">
					<h3>Disclamer</h3>
					<p style = "font-size:10px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, cumque, earum blanditiis incidunt minus commodi consequatur provident quae. Nihil, alias, vel consequatur ab aliquam aspernatur optio harum facilis excepturi mollitia autem voluptas cum ex veniam numquam quia repudiandae in iure. Assumenda, vel provident molestiae perferendis officia commodi asperiores earum sapiente inventore quam deleniti mollitia consequatur expedita quaerat natus praesentium beatae aut ipsa non ex ullam atque suscipit ut dignissimos magnam!</p>

					</div>
				</body>
		</html>';		

	// We check wether the user is accessing the demo locally
	$local = array("::1", "localhost");
	$is_local = in_array($_SERVER['REMOTE_ADDR'], $local);
   $dompdf = new DOMPDF();
  //$dompdf->load_html_file('<p></p>');
   $dompdf->load_html($contnet);
   $dompdf->set_paper('a4','portrait');
   $dompdf->render();
	
	/* If you want password protected */
	//$dompdf->get_canvas()->get_cpdf()->setEncryption("pass", "pass");
	
	/* */
   //$dompdf->stream("invoice.pdf", array("Attachment" =>true)); 
	$invoicelink = 'attachment/invoice.pdf';
	
	$output = $dompdf->output();
		
	file_put_contents($invoicelink,$output);
	
	$emailtosend = 'bharatrose1@gmail.com';
	/* Link of the file */
	//$filelink = 'attachment/invoice.pdf';
	
	$this->PhpMailerSendAttachment($emailtosend,$invoicelink);
	
		
	}
	
	
	public function PhpMailerSendAttachment($emailtosend,$filelink)
	{		
		/* Require php mailer */
		require("phpmailer/class.phpmailer.php");	
		
		/* check file  exist */
		if(!file_exists($filelink))
		{
			/* Return error */
			return printf("%s \r\n","Invoice file not exists $filelink.");
			
		}
		
		
		/* Get class */
		$mail = new PHPMailer(); 		
		$mail->From     = "info@kettycloting.com";
		$mail->AddAddress($emailtosend);
		$mail->Subject  = "Invoice";
		$mail->Body     = "We would like to comfirm you payment, Find invoice attachment with this email.";
		$mail->WordWrap = 50;		
		$mail->AddAttachment($filelink);
		
		if(!$mail->Send())
		{
			return printf("%s \r\n","Could not send email.");
		}
		
		$microtime = microtime();
		
		
		copy($filelink,"copytest/$microtime-invoice.pdf");
		/* Unlink the file */
		unlink($filelink);
		
	}
	
	public function SessionDestroy()
	{
		if($this->SessionSatus() == false)
		{
			session_destroy();
		}
		
		/* Destroy all cookie */
		
	}
	
	public function Register()
	{
		
		$fullname = htmlspecialchars($_POST['fullname']);
		$email = htmlspecialchars($_POST['email']);
   		$address = htmlspecialchars($_POST['address']);
   		$city = htmlspecialchars($_POST['city']);
    	$country = htmlspecialchars($_POST['country']);
    	$username = htmlspecialchars($_POST['username']);
   		$password = htmlspecialchars($_POST['password']);
   		$rpassword =  htmlspecialchars($_POST['rpassword']);
    
		if($password !== $rpassword)
		{
			printf("%s \r\n","<div class = 'note alert-danger'><p>Both password did not matched.</p></div>");
			return false;
		}
		
		/* Check email */
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     	 	printf("%s \r\n","<div class = 'note alert-danger'><p>Invalid email address $email.</p></div>");
			return false;
   	 	}
		/* Get the connection */
		/* Check if content is  more then one then */		
		$connection = $this->DBConfiguration();

		/* select databae */
		$db = $connection->kettyclothing;

		/* Select collection */
		$collection = $db->users;
		
		/* Add some indexs */
		
		$Enpassword = md5($password);
		
		$date = date('Y-m-d H:i:s');
		
		/*
		
		*/
		//$collection->insert($data);
		
		
		/* find if email address already register */
		$er = $collection->findOne(array("email"=>$email));
		
		/* select databae */
		if($er !== NULL)
		{
			printf("%s \r\n","<div class = 'note alert-danger'><p>Email address $email is already registerd.			</p></div>");
			return false;
		}
		
		/* Check the username  */	
		
		$duname = $collection->findOne(array("username"=>$username));
		
		
		
		if($duname !== NULL)
		{
			printf("%s \r\n","<div class = 'note alert-denger'><p>Username $username is already registerd.			</p></div>");
			return false;
		}
		
		$data = array(
    				'fullname' =>$fullname,
    				'email' => $email,
    				'address' =>$address,
    				'city' => $city,
   					 'country' =>$country,
   					 'username' => $username,
    				'password' => $Enpassword,
					'activated'=>'0'
   				 );
		
		$collection->insert($data);
		/* Send activation link to the user  */
		/* But now we are not sending email */
		$connection->close();
		
		printf("%s \r\n","<div class = 'note alert-success'><p>Registration sucess</p></div>");
	}
	
	
	public function RegisterInMysqli()
	{
		ob_start();
		/* Get the all data */
		$fullname 	= htmlspecialchars($_POST['fullname']);
		$email 		= htmlspecialchars($_POST['email']);
   		$address 	= htmlspecialchars($_POST['address']);
   		$city 		= htmlspecialchars($_POST['city']);
    	$country 	= htmlspecialchars($_POST['country']);
    	$username 	= htmlspecialchars($_POST['username']);
   		$password 	= htmlspecialchars($_POST['password']);
   		$rpassword 	=  htmlspecialchars($_POST['rpassword']);
   		
   		/* Get mysqli connetion */
   		$mysqli= $this->MysqliSever1();
   		$fullname 		= $mysqli->real_escape_string($fullname);
   		$email 			= $mysqli->real_escape_string($email);
   		$address 		= $mysqli->real_escape_string($address);
   		$city 			= $mysqli->real_escape_string($city);
   		$country 		= $mysqli->real_escape_string($country);
   		$username 		= $mysqli->real_escape_string($username);
   		$password 		= $mysqli->real_escape_string($password);
   		$rpassword 		= $mysqli->real_escape_string($rpassword);
   		
   		/* Check both password */
   		if($password !== $rpassword)
   		{
			printf("%s \r\n","Both password did not matched.");
			return false;
		}
		
		/* Check in database username */
		if(!$sql = $mysqli->query("select email from users_kettyclothinge_7add4023a86ad"))
		{
			printf("%s \r\n",$mysqli->error);
			return false;
		}
		
		/* Chec email */
		if(($mysqli->affected_rows) != 0)
		{
			printf("%s \r\n","Email address is already registered.");
			return false;
		}
		
		if(!$sql = $mysqli->query("select username from users_kettyclothinge_7add4023a86ad"))
		{
			printf("%s \r\n",$mysqli->error);
			return false;
		}
		
		/* Chec email */
		if(($mysqli->affected_rows) != 0)
		{
			printf("%s \r\n","Username address is already registered.");
			return false;
		}
		
		$date = date('Y-m-d H:i:s');
		if(!$sql = $mysqli->query("INSERT INTO users_kettyclothinge_7add4023a86ad
									(fullname,
									email,
									address,
									city,
									country,
									username,
									password,
									activated,
									date)values
									('$fullname',
									'$email',
									'$address',
									'$city',
									'$country',
									'$username',
									'$password',
									'0',
									'$date'
									)
									"))
		{
			printf("%s \r\n",$mysqli->error);
			return false;
		}
		
   		$mysqli->close();
   		/* Close the connection */
   		ob_clean();
	}
	
	public function login($username, $password)
	{	
		session_start();
		/* Post the date */
		if(isset($_POST[$username]) && isset($_POST[$password]))
		{
			
			$uname = htmlspecialchars($_POST[$username]);
			$upass = md5(htmlspecialchars($_POST[$password]));
			
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;

			/* Select collection */
			$collection = $db->users;
			
			$findone = $collection->findOne(array('username'=>$uname,'password'=>$upass));
			
			if($findone == null)
			{
				
			printf("%s \r\n","<div class = 'note alert-danger'><p>Invalid username and password.</p></div>");
			return false;
			}
			
			/* Chec[k if user is already logged inn */
			if(isset($_SESSION['usernameccf5fc0187']) && isset($_SESSION['passwordccf5fc0187']))
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Your are already logged in</p></div>");
				return false;
			}
			
			$_SESSION['usernameccf5fc0187'] = $uname;
			$_SESSION['passwordccf5fc0187'] = $upass;
			//printf("%s \r\n","<div class k 'note alert-success''lk>Sucespls</p></div>");
			
			echo 'loggedin';
			
		}
	}
	
	public function IfLoggedIn()
	{	
		session_start();
		/* Chec[k if user is already logged inn */
			if(isset($_SESSION['usernameccf5fc0187']) && isset($_SESSION['passwordccf5fc0187']))
			{
				header('Location:index');
			}
	}
	
	public function logout()
	{
		if(isset($_GET['session_destroy']) && $_GET['session_destroy'] == '1')
		{
				
			if(isset($_SESSION['usernameccf5fc0187']) && isset($_SESSION['passwordccf5fc0187']))
			{
				unset($_SESSION['usernameccf5fc0187']);
				unset($_SESSION['passwordccf5fc0187']);				
				header('Location:index?logged_out=1');
			
			}
		}

		
	}
	
	public function logoutbtn()
	{		
		if(isset($_SESSION['usernameccf5fc0187']) && isset($_SESSION['passwordccf5fc0187']))
			{
				echo '<li class="">
                            <a href="index?session_destroy=1" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>';
			}
		
	}

	public function ResetPasswordRequest($emailaddress)
	{
		// check if email address posted 
		if(isset($_POST[$emailaddress]))
		{	
			$email = htmlspecialchars($_POST[$emailaddress]);
			
			/* Check that email */
			$regrex = '/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU';
			
			if(!preg_match($regrex, $email))
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Invalid email address.</p></div>");
				
				return false;
			
			}
			
			/* Check that emil address exists */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;

		/* Select collection */
			$collection = $db->users;
			
			$femail = $collection->findOne(array('email'=>$email));
			
			if($femail == null)
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Unregistered email address.</p></div>");
				
				return false;
			}
			
			$id = '';
			$id =  $femail['_id'];
			// Close 
			$connection->close();
			
			$date = date('Y-m-d H:i:s');
			
			/* Create headers to send email */
			// subject
			$subject = 'Rest Your password';
			// message
			$message = '
			<html>
			<head>
			  <title>Reset Password</title>
			</head>
			<body>
			  <p>Please click the link below to reset you password.</p>
			 < a href = "http://localhost/neproject/resetpassword.php?id='.$id.'&date='.$date.'&email='.$email.'"></a>
			</body>
			</html>
			';
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			$headers .= "To:$email" . "\r\n";
			$headers .= 'From:info@kettyclothing.com' . "\r\n";
			
			// Mail it 
			if(!mail($email, 'Hi', 'sdf'))
			{
				// Error 
				printf("%s \r\n","<div class = 'note alert-danger'><p>Could not send email.</p></div>");		
				return false;
			}
			
			// Error 
			printf("%s \r\n","<div class = 'note alert-success'><p>Email sent check you inbox.</p></div>");		
				
		 }
		
		
	}
	
	/* Reset password */
	public function resetPassword($id, $date, $email)
	{
	
		
		/* Must set refere for this script */
		if(!isset($_SERVER['HTTP_REFERER']))
		{
			return false;	
		}
	
	
		if(isset($_GET[$id]) && isset($_GET[$date]) && isset($_GET[$email]))
		{
			
			$gid = $_GET[$id];
			$gdate = $_GET[$gdate];
			
			/* Add 7 days to the get date */
			$cdate = strtotime('+ 7 days');
			
			if($gdate > $cdate)
			{
			printf("%s \r\n","<div class = 'note alert-danger'>
			<p>Link experied, Please request new password.</p></div>");
			return false;
			}
			
			$gid = htmlspecialchars($gid);
			
			/* Check in data base id exist with same email address */
			/* Check that emil address exists */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;

			/* Select collection */
			$collection = $db->users;
			
			$femail = $collection->findOne(array('email'=>$email,'_id'=>new MongoId($gid),'id'=>$id));
			
			
			if($femail == null)
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Request failed, 
									Can not find matched.</p></div>
									");				
				return false;
			}
			
			$connection->close();
			
			// Return true;
		}
	}
	
	public function isLogIn()
	{
		if(isset($_SESSION['usernameccf5fc0187']) && isset($_SESSION['passwordccf5fc0187']))
		{
			return true;
		}
		
		return false;
	}

	/* Showing cart in menu */
	public function CartMenu($sessionname)
	{
		/* This will run after getting request from paypal */
		$this->GetCurrency();
		
		/* Only run the script if session set */
		if(isset($_SESSION[$sessionname]) && count($_SESSION[$sessionname]) > 0)
		{	
		
		/* start to fetch the data */
		$this->cartsessiondata = 	$_SESSION[$sessionname];
		
		for($j = 0; $j < count($this->cartsessiondata); $j++)
		{
			$this->cartsessiondata[$j]["price"] = $this->cartsessiondata[$j]["price"] * $this->currencyvalue;
			$this->cartsessiondata[$j]["totalamount"] = $this->cartsessiondata[$j]["totalamount"] * $this->currencyvalue;
		}

		
		$totalItems = '';
		$totalamount = '';
		foreach($this->cartsessiondata as $eachItems)
		{
			$totalItems += $eachItems["qty"];
			$totalamount += $eachItems["totalamount"];		
			
		}
		
		
		$this->totalItems = $totalItems;
		$this->totalamount = $totalamount;
		// setting html element 
		
		$table = '<table class = "table table-striped table-bordered table-advance table-hove custom-t">
					<thead>					
					<tr>												
					<th>Name</th>
					<th>Qty </th>
					<th>Price</th>																	
					<th>Total </th>					
				</tr>
			</thead>';
			
		$htmlelement = '<tr>
					<th class="t-body"><small><a href="assets/global/plugins/cubeportfolio/ajax/project1.php?id={{&id}}&sku={{&sku}}" class="cbp-singlePage" rel="nofollow">{{&productname}}</a></small></th>                                           
					<th class="t-body"><small>{{&qty}}</small></th>
					<th>'.$this->currencycode.'{{&price}} '.$this->currency.'</th>
					<th>'.$this->currencycode.'{{&totalamount}} '.$this->currency.'</th>      
				</tr>';
			
		$regrex = '/{{&(.*?)}}/';
		
		
		$divsec = '<thead>
						<tr>
							<th>Total</th>
							<th>'.$this->totalItems.'</th>
							<th></th>
							<th>'.$this->currencycode.''.$this->totalamount.' '.$this->currency.'</th>

						</tr>
						<tr>
						<th></th>
						<th></th>
						<th></th>
						<th>'.$this->PayPalAPI($sessionname).'</th>
						</tr>
					</thead>
					
				</table>';
		
		$template  = $this->getTemplate($htmlelement,$this->cartsessiondata,$regrex);
		
		$html = '';
		$html .= $table;
		$html .= $template;
		$html .= $divsec;
		
		
		return $html;
			
		}	
		else
		{
			return '<table class = "table table-striped table-bordered table-advance table-hove custom-t">
					<tr><div class = "note alert-info">
					<p>Cart is empty.</p>
					</div>
					</tr>
					</table>';
			
		}	
		
	}
	
	public function ContCart($sessionname)
	{
		
		if(isset($_SESSION[$sessionname]) && count($_SESSION[$sessionname]) > 0)
		{
			return count($_SESSION[$sessionname]);
		}
		else
		{
			return 0;
		}
	}
	
	public function updateCart($sessionname, $sku, $qty, $id)
	{
		session_start();
		if(isset($_SESSION[$sessionname]) && isset($_GET[$sku])  && isset($_GET[$id])  && isset($_GET[$qty]))
		{
			$gid = $_GET[$id];
			$gsku = $_GET[$sku];
			$gqty = $_GET[$qty];
			
			if($gqty > 100)
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Quentity must be less then 100.</p></div>");
				return false;
			}
			
			$i = 0;			
			$index = '';
			if($_SESSION[$sessionname]  > 0)
			{
				foreach($_SESSION[$sessionname] as $eachitem)
				{
					if($eachitem['id'] == $gid)
					{
						$index = $i;
					}
					
					$i++;
				}
				
				
				$_SESSION[$sessionname][$index]['qty'] = $_SESSION[$sessionname][$index]['qty']+$gqty;
				
				$price = $_SESSION[$sessionname][$index]['price'];
				
				$_SESSION[$sessionname][$index]['totalamount'] =  $_SESSION[$sessionname][$index]['totalamount'] +($gqty) * $price;
				
				
				
			}
			
		}
	}
	
	public function deleteCart($sessionname, $sku, $qty, $id)
	{
		session_start();
		if(isset($_SESSION[$sessionname]) && isset($_GET[$sku])  && isset($_GET[$id])  && isset($_GET[$qty]))
		{
			$gid = $_GET[$id];
			$gsku = $_GET[$sku];
			$gqty = $_GET[$qty];
			
			if($gqty > 100)
			{
				printf("%s \r\n","<div class = 'note alert-danger'><p>Quentity must be less then 100.</p></div>");
				return false;
			}
			
			$i = 0;			
			$index = '';
			if($_SESSION[$sessionname]  > 0)
			{
				foreach($_SESSION[$sessionname] as $eachitem)
				{
					if($eachitem['id'] == $gid)
					{
						$index = $i;
					}
					
					$i++;
				}
				
				if($_SESSION[$sessionname][$index]['qty'] < $gqty)
				{
					printf("%s \r\n","<div class = 'note alert-danger'><p>Can not delete product.</p></div>");
					return false;
				}elseif($_SESSION[$sessionname][$index]['qty']  == $gqty)
				{
					unset($_SESSION[$sessionname][$index]);
					sort($_SESSION[$sessionname]);
					return;
				}
				
				
				
				$_SESSION[$sessionname][$index]['qty'] = $_SESSION[$sessionname][$index]['qty']-$gqty;
				
				$price = $_SESSION[$sessionname][$index]['price'];
				
				$_SESSION[$sessionname][$index]['totalamount'] =  $_SESSION[$sessionname][$index]['totalamount'] -($gqty) * $price;
				
				
				
			}
			
		}
	}
	
	public function CountDbData()
	{
	/* Ge the connecton */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$dcollection = $db->kettyclothingdb;
		
			/* Find the all element */
			$find = $dcollection->find();
			
			$collection = $db->product_images;
		
			/* count de collection */
			$countrec = $dcollection->find()->count();	
			
			$connection->close();
			
			return $countrec;
	}
	
	
	public function ReadProductforuserByPriceRng($limit,$lowprice,$highprice)
	{		
		/* Set the page */
		$page = 0;
		
		/* Set the limit */
		//$limit = 10;
		
		/* If get page set get it */
		if(isset($_GET['page']))
		{
			/* Get the page */
			$page = $_GET['page'];
		}	
		
		/* Skip page from */
		$skipfrom =  $page * $limit;
			
		$this->DBConfiguration();
		
			/* Set the template */
			$template = '<!-- This line will loop -->
							<div class = "col-md-3"> 
								<div class = "each-product">
								{#img}
								</div>
								
							<div class = "product-footer" style = "text-align:center;">
							
							<input type = "hidden" 
							<a href = "ecommerce_products_delete?id={@_id}&sku={@sku}"><button value = "Delete" class = "btn btn-sm btn-success filter-submit margin-bottom">Delete</button></a>
							<a href = "ecommerce_products_edit?id={@_id}&sku={@sku}"><button value = "Update" class = "btn btn-sm btn-success filter-submit margin-bottom">Update</button></a>
							</div>
							</div>						
					
						</div>											
					</div>  
                     ';
			/* Ge the connecton */
			$connection = $this->DBConfiguration();

			/* select databae */
			$db = $connection->kettyclothing;
		
			/* Select collection */
			$dcollection = $db->kettyclothingdb;
			
			
				$rows = $dcollection->find()->count();
			
				// Get the random number between that 
				$rnd = rand(0, $rows);
		
				$skipfrom = $rnd;
			
				$find = $dcollection->find(array('price'=>array('$gte'=>$lowprice,'$lte'=>$highprice)))->limit($limit)->skip($skipfrom);
		
				//$db->users->find(array("age" => array('$gte' => 33, '$lte' => 40)));
				
			
			/* Find the all element */
			//$find = $dcollection->find()->limit($limit)->skip($skipfrom);
			
			$collection = $db->product_images;
			
			/* Check if there is product */
			$coundrows = $dcollection->find()->count();
			
			/* If less then 1 return false  */
			if($coundrows < 1)
			{
				printf("%s \r\n","<div class = 'note note-danger'>
 	 				<p> NOTE: No product avaialbe to fetch.  </p>
					</div>");
				return false;
			}
		
			/* Get the colums names */
		
			$keys = '';
			foreach($find as $key => $value)
			{
				
				$keys = array_keys($value);
			}
			
			
			/* Extract the value that trying to get */
			
			/* Get everyhing between {}*/
			$reg = '/{@(.*?)}/';
	
			/* Set the matches */
			$matches = array();
			
			if(preg_match_all($reg, $template, $matches))
			{
				
			}
			
			/* Get the unique value */
			$uvalue = array_unique($matches[1]);
			$diffvalue = '';
			if(is_array($keys))
			{
				/* Find the different */
				$diffvalue = array_diff($uvalue, $keys);
			}
			
			
			/* Cound must be 0 */
			if(count($diffvalue) > 0)
			{
				/* Error */
				printf("%s \r\n","Undefined indexs ".implode(',', $diffvalue)." in database.");
				return false;
			}
		
			
			
			
			foreach($find as $key=> $value)
			{			
				$sku = $value["sku"];
				$this->data[] = $value;
				
			}
		
			/* Select the product images */
			$findimages = $collection->find()->limit($limit)->skip($skipfrom);
			
			
			foreach($findimages as $key=> $value)
			{
				foreach($value as $k => $v)
				{
					if($k == '_id')
					{
						continue;
					}

					$this->productimage[$k] = $v[0];
				}
				

			}
		
		
		$dir = '';
		/* If there is script filename */
		
			/* Directory should be one step back */
			$dir = "img/product-images";
		
		
	
		
		foreach($this->productimage as $key => $value)
		{
			$link = $dir."/$key/$value";
			
			$this->productimage[$key] = $link;
			
		}
		
		$this->productimage = array_values($this->productimage);
		
		//return false;
		//echo "<pre>"		
		for($i = 0; $i < count($this->data); $i++)
		{
			array_push($this->data[$i],$this->productimage[$i]);
		}
	}	
	
	public function fetchByPriceRange($lowprice, $highprice)
	{
		/* Fetch the product */
		$this->ReadProductforuser(10);;
		
		/* If data set by the user or not */
		if(isset($_POST[$lowprice]) && isset($_POST[$highprice]))
		{
				$glprice = $_POST[$lowprice];
				$ghighprice = $_POST[$highprice];				
				
				settype($glprice,'integer');
				settype($ghighprice,'integer');
				
				$limit = 10;
				
				$newData = $this->PriceRange($ghighprice,$glprice, $this->data);
				
				
				$data = '<div class = "col-md-3"> 
					<div class = "each-product">
						<a href="assets/global/plugins/cubeportfolio/ajax/project1.php?id={{&_id}}&sku={{&sku}}" class="cbp-singlePage" rel="nofollow"><img src = "{{&0}}" /></a>
					</div>					
					<div class = "product-footer" style = "text-align:center;">						
											
					</div>
				</div>';					

		
		$regrex = '/{{&(.*?)}}/';
		
		$div = '';
		//$div = '<div style="height: 328px;" id="js-grid-juicy-projects" class="cbp cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">';
		
		$div .= $this->getTemplate($data, $newData,$regrex);
		//$div .= '</div>';
		
		 
		return $div;
				
				
		}
	}	

	public function LowToHighFetch($data, $method)
	{
		 return ($data["price"] <= $method["price"]) ? -1 : 1;
	}

	public function PriceRange($high, $low, $array)
	{	
		
		foreach($array as $key=>$value)
		{
			if($value['price'] <= $high && $value['price'] >= $low)
			{
				$this->DataByPriceRange[$key] = $value;
			}
		}
		
		/* Return the data */
		return $this->DataByPriceRange;
	}
	
	public function PaginationByPriceRange($high,$low)
	{
		if(isset($_GET[$high]) && isset($_GET[$low]))
		{
			
			$this->fetchByPriceRange($low, $high);
			
			$datcount = count($this->data);
						
			return $this->pagination($datcount);
		}
		
	}
		
	/* Inserting data in mysqli */	
	public function MysqliSever1()
	{
		/* Required the for connection */
		require_once 'mysqli-server.php';
		
		/* Get mysqli connection */
		$obj = new MySQLServer();
		
		/* Create required tables */
		return $obj->CreateRequiredTables();
		
		/* Return the method */
		
	}
	
	public function InsertInMysqli()
	{
		/* Start the object */
		ob_start(); 
		
		/* Get the connection */
		$mysqli = $this->MysqliSever1();
		
		/* Accept the request */
		if($this->AcccepReqest() !== true)
		{
			return  printf("%s \r\n",$this->AcccepReqest());
		}
		
		/* Table name */
		$tablename = 'product_kettyclothing_kettyclothinge_7add4023a86ad';
		
		
		/* Get all vairalbe */
		if(!$sql = $mysqli->query("SELECT * FROM $tablename"))
		{
				/* Throw error */
				printf("%s \r\n", $mysqli->error);
				return false;
		}		
		
		/* Get the all data */
		$productname 		= $mysqli->real_escape_string($this->productname); 
		$description 		= $mysqli->real_escape_string($this->discription);
		$shortdiscription 	= $mysqli->real_escape_string($this->shortdiscription);
		$availabledatefrom 	= $mysqli->real_escape_string($this->availabledatefrom);
		$availabledateto 	= $mysqli->real_escape_string($this->availabledateto);
		$sku 				= $mysqli->real_escape_string($this->sku);
		$price 				= $mysqli->real_escape_string($this->price);
		$taxclass 			= $mysqli->real_escape_string($this->taxclass);
		$status 			= $mysqli->real_escape_string($this->status);
		
		/* This is new product adding */
		$special_price		    = 	$mysqli->real_escape_string($this->special_price);
		$available_items 		= 	$mysqli->real_escape_string($this->available_items);
		$origin 				= 	$mysqli->real_escape_string($this->origin);
		$lining 				= 	$mysqli->real_escape_string($this->lining);
		$material 				= 	$mysqli->real_escape_string($this->material);
		$condition				= 	$mysqli->real_escape_string($this->condition);
		$style 					= 	$mysqli->real_escape_string($this->style);
		$season 				= 	$mysqli->real_escape_string($this->season);
		$product_feature 		= 	$mysqli->real_escape_string($this->product_feature);
		$productLable 			= 	$mysqli->real_escape_string($this->productLable);
		$colors 				= 	$mysqli->real_escape_string($this->colors);
		$sizes 					= 	$mysqli->real_escape_string($this->sizes);
		$buttoncolor 			= 	$mysqli->real_escape_string($this->buttoncolor);
		$avaibility 			= 	$mysqli->real_escape_string($this->avaibility);
		$collection 			= 	$mysqli->real_escape_string($this->collectionu);
		$weight 				= 	$mysqli->real_escape_string($this->weight);
		
		
		/* Check if product sek exit */
		if(!$sql = $mysqli->query("SELECT sku from $tablename where sku = '$sku' limit 1" ))
		{
			/* Throw error */
			printf("%s \r\n", $mysqli->error);
			return false;
		}
		
		/* Check rows must be row */
		if(($mysqli->affected_rows) > 0)
		{
				/* Throw error */
			printf("%s \r\n","Product sku $sku already exists.");
			return false;
		}
		
		/* Get current date */
		$date = date('Y-m-d H:i:s');
		
		/* Insert data */
		if(!$sql = $mysqli->query("INSERT INTO $tablename
										(
										productname,
										description,
										shortdiscription,
										availabledatefrom,
										availabledateto,
										sku,
										price,
										taxclass,
										status,
										date,
										special_price,
										available_items,
										origin,
										material,
										p_condition,
										style,
										season,
										product_feature,
										productLable,
										colors,
										sizes,
										buttoncolor,
										avaibility,
										collection,
										weight
										)values
										(
										'$productname',
										'$description',
										'$shortdiscription',
										'$availabledatefrom',
										'$availabledateto',
										'$sku',
										'$price',
										'$taxclass',
										'$status',
										'$date',
										'$special_price',
										'$available_items',
										'$origin',
										'$material',
										'$condition',
										'$style',
										'$season',
										'$product_feature',
										'$productLable',
										'$colors',
										'$sizes',
										'$buttoncolor',
										'$avaibility',
										'$collection',
										'$weight'									
										)"))
			{
				/* Throw message */
				printf("%s \r\n",$mysqli->error);
				return false;
			}
			
		/* Close connetion */
		$mysqli->close();
		
		/* Clean the object */
		ob_clean();		
	}	
	
	public function CartName()
	{
		
		return md5($this->CartNameString());
	}
	
	public function CartNameString()
	{
		$cartname = 'KettyClothing2656';
		
		return $cartname;	
	}

		/* If you need shopping cart in cookie then use the method */
	public function 		SetCartAsCookie($id,$sku,$qty,$price,$productname,$userbtncolor,$regularprice,$color,$size,$primary_image)
	{			
		//$this->GetCurrency();

		//echo $this->currency;
		//echo $this->currencycode;
		//echo $this->currencyvalue;
		/* Get the product key */
		
		if(isset($_REQUEST[$id]) && isset($_REQUEST[$sku]) && isset($_REQUEST[$qty]))
		{
			$gid = htmlspecialchars($_REQUEST[$id]);
			$gsku =	htmlspecialchars($_REQUEST[$sku]);
			$qty =	htmlspecialchars($_REQUEST[$qty]);
			$price = htmlspecialchars($_REQUEST[$price]);
			$gproductname = htmlspecialchars($_REQUEST[$productname]);
			$userbtncolor = htmlspecialchars($_REQUEST[$userbtncolor]);
			$regularprice = htmlspecialchars($_REQUEST[$regularprice]);
			$color = htmlspecialchars($_REQUEST[$color]);
			$size = htmlspecialchars($_REQUEST[$size]);
			$product_image = $_REQUEST[$primary_image];
			
			/* Qty just must be less or geter then 100, 0*/
			if($qty < 1 && $qty > 100)
			{
				printf("%s /r/n","Quentity must be between 1 to 100.");
				return false;
			}
		
		
			/* Set color in array */
			$colorarray  = $this->repeatString($color, $qty);
			
			/* Set size in array */
			$sizearray =  $this->repeatString($size, $qty);
			
			/* Set button color in array */
			$buttoncolorarray =   $this->repeatString($userbtncolor, $qty);
			
			/* For color, button color and size index value should be array */
		
			if(!isset($_COOKIE[$this->CartName()]))
			{			
				$cartdata = array(0=>array("id"=>$gid,
															"productname"=>$gproductname,
															"sku"=>$gsku,
															"qty"=>$qty,
															"price"=>$price,
															"totalamount"=>$qty*$price,
															"userbtncolor"=>$buttoncolorarray,
															"regularprice"=>$regularprice,
															"color"=>$colorarray,
															"size"=>$sizearray,
															"discount"=>($regularprice - $price) * $qty,
															"product_image" => $product_image
															));
				
				$cartdata = json_encode($cartdata);
															
				setcookie($this->CartName(),$cartdata ,time() +3600, '/', $_SERVER['SERVER_NAME'] );
				
				return true;
			}
			
			$sameProduct = false;
			$i = 0;
		
		
		$_COOKIE[$this->CartName()] = json_decode($_COOKIE[$this->CartName()],1);
			
		foreach($_COOKIE[$this->CartName()] as $eachItem)
		{
			/* Increase $i by ++*/
			$i++;
			
			/* List each index */ 
			while(list($key,$value) = each($eachItem))
			{
				/* If value pair matched */
				if($key == "id" && $value == $gid)
				{					
					
				/* Replace the index with new value by adding previouse value */			
				array_splice($_COOKIE[$this->CartName()],$i-1,1,array(
				array(
						"id"=>$gid,
						"productname"=>$gproductname,
						"sku"=>$gsku,
						"qty"=>$eachItem["qty"]+$qty,
						"price"=>$price,
						"totalamount"=>$eachItem["totalamount"]+$qty*$price,
						"userbtncolor"=>$eachItem["userbtncolor"]."&@,".$buttoncolorarray,
						"regularprice"=>$regularprice,
						"color"=>$eachItem["color"]."&@,".$colorarray,
						"size"=>$eachItem["size"]."&@,".$sizearray,
						"discount"=>$eachItem['discount']+($regularprice - $price) * $qty,
						"product_image" => $product_image
				)
			)
		);			
				/* Encode data now */
				$_COOKIE[$this->CartName()] = json_encode($_COOKIE[$this->CartName()]);
				
				/* Set cookie */
				setcookie($this->CartName(),$_COOKIE[$this->CartName()] ,time() +3600, '/', $_SERVER['SERVER_NAME'] );
				
				/* Set variable to true */
				$sameProduct = true;					
				}
				
				
			}				
		}
		
		if($sameProduct == false)
		{
			// push then new index in the array 
			$getcookie = $_COOKIE[$this->CartName()];			
			
			/* Push the value in array */
			array_push($_COOKIE[$this->CartName()],array(
															"id"=>$gid,
															"productname"=>$gproductname,
															"sku"=>$gsku,
															"qty"=>$qty,
															"price"=>$price,
															"totalamount"=>$qty*$price,
															"userbtncolor"=>$buttoncolorarray,
															"regularprice"=>$regularprice,
															"color"=>$colorarray,
															"size"=>$sizearray,
															"discount"=>($regularprice - $price)*$qty,
															"product_image" => $product_image
															)
														);
		
		/* Encode the array data in json format */
		$_COOKIE[$this->CartName()] = json_encode($_COOKIE[$this->CartName()]);
			
		/* Set cookie in client broweser */
		setcookie($this->CartName(),$_COOKIE[$this->CartName()] ,time() +3600, '/', $_SERVER['SERVER_NAME'] );
	
		}		
	}	
		
		
	return true;
	
	}

	public function KettyShowCart($session_name)
	{
				/* Start the session */
				session_start();				

				/* Check if session set */
				if(isset($_SESSION[$session_name]) && count($_SESSION[$session_name]) > 0)
				{					
					/* Get the total amount */				
					
					/* Get the session */
					$indextoget = 'userbtncolor';
					$elementToshow = "<h4 style = 'display:inline; text-transform:lowercase;'><i class = 'fa fa-pencil' 	style = 'color:{{&putcolorhere}};'></i></h4>";
					$indextoset = 'htmlbtncolor';
					
					$cart = $this->userSelectColor($session_name, $indextoget, $elementToshow, $indextoset);
					
					$cart = $this->userSelectColor($session_name, 'color', $elementToshow, 'htmlcolor');
					//echo "<pre>";
					//print_r($cart);
					//echo "</pre>";
					
					/* Get product skue */
					$sku = $this->getTemplate("{{&sku}}", $cart, "/{{&(.*?)}}/").",";
					
					/* get the id */
					$id = $this->getTemplate("{{&id}}", $cart, "/{{&(.*?)}}/").",";
					
					
					/* Get the color */
					//$colors = $this->getProductColors($id,$sku);
				
					/* Put the session data in array */
					$sessionData = $cart;
					
					/* Get the product avaialbe color in html */
					$htmlcolorcode = $this->getTemplate('{{&htmlcolorcode}}',$sessionData, '/{{&(.*?)}}/');
						
				
					
					/* Html data */
					$html = '<tr class="first odd">
<td class="product-cart-image">
<a href="../../product-details?id={{&id}}&sku={{&sku}}" title="Striped cotton shirt" class="product-image">

<img src="../../{{&product_image}}" alt="Striped cotton shirt">
</a>

<ul class="cart-links">
<li>
<a href="http://livedemo00.template-help.com/magento_53638/checkout/cart/configure/id/2025/" title="Edit item parameters">Edit</a>
</li>
</ul>
</td>
<td class="product-cart-info">
<a href="http://livedemo00.template-help.com/magento_53638/checkout/cart/delete/id/2025/uenc/aHR0cDovL2xpdmVkZW1vMDAudGVtcGxhdGUtaGVscC5jb20vbWFnZW50b181MzYzOC9jaGVja291dC9jYXJ0Lw,,/" title="Remove Item" class="btn-remove btn-remove2">Remove Item</a>
<h2 class="product-name">


<a href="../../product-details?id={{&id}}&sku={{&sku}}">{{&productname}}</a>
</h2>



<div class="product-cart-sku">
<span class="label">SKU:</span> {{&sku}}</div>
<dl class="item-options">
<dt>Buttons color</dt>
<dd>{{&htmlbtncolor}}</dd>
<dt>Lining color</dt>
<dd>Light </dd>

<dt>Color</dt>
<dd>{{&htmlcolor}}</dd>
</dl>
</td>
<td class="product-cart-price" data-rwd-label="Price" data-rwd-tax-label="Excl. Tax">
<span class="cart-price">
<span class="price">${{&price}}.00</span>
</span>
</td>
	
<td class="product-cart-actions" data-rwd-label="Qty">
<input pattern="\d*" name="cart[2025][qty]" value="{{&qty}}" size="4" title="Qty" class="input-text qty" maxlength="12" type="text" id = "qty{{&id}}" onfocus = "product_dis_cart(this);" focusuniquekey = {{&id}} >		


<div class = "product-dis-details" id = "hold_product_dis{{&id}}" style = "display:none;">
{{&htmlcolorcode}}
{{&htmlbtncolorcode}}
{{&htmlsizescode}}
</div>

<button class="button btn-update" buttonurl = "http://localhost/newproject3/ajax/updatecart.php?&id={{&id}}&sku={{&sku}}" onclick = "UpdateCart(this);return false;" uniquekey = {{&id}}><span><span>Update</span></span>
</button>
</td>
	<td class="product-cart-total" data-rwd-label="Subtotal">
<span class="cart-price">
<span class="price" style = "color:green;">${{&discount}}.00</span>
</span>
</td>

<td class="product-cart-total" data-rwd-label="Subtotal">
<span class="cart-price">
<span class="price">${{&totalamount}}.00</span>
</span>
</td>


<td class="a-center product-cart-remove last">
<a href="http://localhost/newproject3/ajax/remove-product.php?id={{&id}}&sku={{&sku}}" title="Remove Item" class="btn-remove btn-remove2" onclick = "removeproduct(this);return false;">Remove Item</a>
</td>
</tr>';
				/* Set regression */
				$regression = '/{{&(.*?)}}/';
				/* Set the template */
				
				$data = $this->getTemplate($html, $sessionData, $regression);
				
				return $data;
				}
				else
				{
					/* Return false */
					return false;
				}
				
			}
		
	/* Get user select color in html */
	public function userSelectColor($session_name, $indextoget, $elementToShow, $indextoset)
	{	
		
		/* Get the session data */
		if(isset($_SESSION[$session_name]) && count($_SESSION[$session_name]) > 0)
		{
			/* Get the session data */
			$session_data = $_SESSION[$session_name];
			
			/* Regression for hexa color */
			$reg = '/^#[\w\d]{6}$/';
			/* Need to get two thing */
			for ($j = 0; $j < count($session_data); $j++)
			{
				
				/* Check if pettern match */
				if(preg_match($reg,$session_data[$j][$indextoget]))
				{
					$btncolor = $session_data[$j][$indextoget];
					/*$allbutton = "<h4 style = 'display:inline;'><i class = 'fa fa-pencil' style = 'color:$btncolor;'></i></h4>";
					*/
					$allbutton = str_replace('{{&putcolorhere}}', $btncolor, $elementToShow);
					
					
				
				}
				elseif(strpos($session_data[$j][$indextoget],'&@,'))
				{
					$allbutton = '';
					
					/* trim the data */
					$userbtncolor = trim($session_data[$j][$indextoget]);
					
					/* Expload the color */
					$expuserbtncolor = explode('&@,',$userbtncolor);
					
					/* Get the array unique color */
					$unicolor = array_count_values($expuserbtncolor);
					
					foreach($unicolor as $key => $value)
					{
							/*$allbutton .= "<h4 style = 'display:inline;'><i class = 'fa fa-pencil' style = 'color:$key;'> Qty:$value </i></h4> &nbsp;&nbsp;&nbsp; ";
							*/
						$allbutton .= str_replace('{{&putcolorhere}}', $key, $elementToShow);
						
					}
				}
				else
				{
					$allbutton = '';
				}
			
			//echo $allbutton;
			
			// push the value 
			$_SESSION[$session_name][$j][$indextoset] = $allbutton;
				
				
			}
			
		return $_SESSION[$session_name];
		}
		
		
		
	}
	
	public function KettyCartTotal($session_name)
	{
		$carttotalqty = '';
		$carttotalamount = '';
		/* IF session set */
		if(isset($_SESSION[$session_name]))
		{
			
			/* Loop */
			foreach($_SESSION[$session_name] as $eachitems)
			{
				$carttotalqty +=  $eachitems['qty'];
				$carttotalamount += $eachitems['totalamount'];
			}
		}
		
		$CartTotalInArr = array(array('carttotalqty' => $carttotalqty, 'carttotalamount' => $carttotalamount));
		
		return $CartTotalInArr;
	}

}
function HighToLowFetch($data, $method)
{
	return ($data["price"] >= $method["price"]) ? -1 : 1;
	}
?>
<?php
	class KettyClothing extends UploadProduct
	{
		public $majorimg = [];
		public $subimgketty = [];
		public $OrderRecordNum;
			/* Fetch the product for user */
			
			/* Validate all data */
			
			public function fetchProductKettyMysqli()
			{
					/* Required mysqli */
					$mysqli = parent::MysqliSever1();
					
					/* Ge the date */
					$tablename = 'product_kettyclothing_kettyclothinge_7add4023a86ad';
					
					/* Run the query */
					if(!$sql = $mysqli->query("select * from $tablename"))
					{
							/* Error */
							printf("%s \r\n",$mysqli->error);
							return false;
					}
					
					/* Fetch each rows */
					while($row = $sql->fetch_assoc())
					{
						$this->data = $row;
					}
					
					/* Select */
					/* Close conncection */
					$mysqli->close();
					
					
			}
	
			public function fetchProductKettyMongo()
			{
					/* Fetch product for user */
					$this->ReadProductforuserKetty();
					
					
			}			
			
			public function ReadProductforuserKetty()
			{		
				/* Set the page */
				$page = 0;
				
				/* Set the limit */
				$limit = 1;
				
				/* If get page set get it */
				if(isset($_GET['page']))
				{
					/* Get the page */
					$page = $_GET['page'];
				}		
				/* Skip page from */
				$skipfrom =  $page * $limit;
					
				$this->DBConfiguration();
					/* Set the template */
					$template = '<!-- This line will loop -->
									<div class = "col-md-3"> 
										<div class = "each-product">
										{#img}
										</div>
										
									<div class = "product-footer" style = "text-align:center;">
									
									<input type = "hidden" 
									<a href = "ecommerce_products_delete?id={@_id}&sku={@sku}"><button value = "Delete" class = "btn btn-sm btn-success filter-submit margin-bottom">Delete</button></a>
									<a href = "ecommerce_products_edit?id={@_id}&sku={@sku}"><button value = "Update" class = "btn btn-sm btn-success filter-submit margin-bottom">Update</button></a>
									</div>
									</div>						
							
								</div>											
							</div>  
							 ';
					/* Ge the connecton */
					$connection = $this->DBConfiguration();

					/* select databae */
					$db = $connection->kettyclothing;
				
					/* Select collection */
					$dcollection = $db->kettyclothingdb;
					
					
					$rows = $dcollection->find()->count();
				
					// Get the random number between that 
					$rnd = rand(0, $rows);
			
					$skipfrom = $rnd;
				
					$find = $dcollection->find();
				
						
					
					/* Find the all element */
					//$find = $dcollection->find()->limit($limit)->skip($skipfrom);
					
					$collection = $db->product_images;
					
					/* Check if there is product */
					$coundrows = $dcollection->find()->count();
					
					/* If less then 1 return false  */
					if($coundrows < 1)
					{
						printf("%s \r\n","<div class = 'note note-danger'>
							<p> NOTE: No product avaialbe to fetch.  </p>
							</div>");
						return false;
					}
				
					/* Get the colums names */
				
					$keys = '';
					foreach($find as $key => $value)
					{
						
						$keys = array_keys($value);
					}
					
					
					/* Extract the value that trying to get */
					
					/* Get everyhing between {}*/
					$reg = '/{@(.*?)}/';
			
					/* Set the matches */
					$matches = array();
					
					if(preg_match_all($reg, $template, $matches))
					{
						
					}
					
					/* Get the unique value */
					$uvalue = array_unique($matches[1]);
					$diffvalue = '';
					if(is_array($keys))
					{
						/* Find the different */
						$diffvalue = array_diff($uvalue, $keys);
					}
					
					if(gettype($diffvalue) == 'string')
					{
						return false;
					}
					/* Cound must be 0 */
					if(count($diffvalue) > 0)
					{
						/* Error */
						printf("%s \r\n","Undefined indexs ".implode(',', $diffvalue)." in database.");
						return false;
					}
				
					
					
					
					foreach($find as $key=> $value)
					{			
						$sku = $value["sku"];
						$this->data[] = $value;
						
					}
				
					if(count($this->data) < 1)
					{
						return false;
					}
				
					/* Select the product images */
					$findimages = $collection->find();
					
					
					foreach($findimages as $key=> $value)
					{
						foreach($value as $k => $v)
						{
							if($k == '_id')
							{
								continue;
							}

							$this->productimage[$k] = $v;
							$this->majorimg[$k] = $v[0];
						}
						

					}
				
				
					$dir = '';
				/* If there is script filename */
				
					/* Directory should be one step back */
					$dir = "img/product-images";	
					
					/*
					 * <li class='product-thumb'>
<a href='media/catalog/product/cache/1/small_image/290x359/9df78eab33525d08d6e5fb8d27136e95/f/l/flared_jeans_6.jpg'>
<img src='media/catalog/product/cache/1/thumbnail/44x55/9df78eab33525d08d6e5fb8d27136e95/f/l/flared_jeans_6.jpg' alt=''/>
</a>
</li>
					 * */	
				/* This div for product details */
				
				foreach($this->productimage as $key => $value)
				{
					$subimgdiv = "<div id='gallery' style='display:none;' data-imagezoom={'active':'1','zoom_variant':'unitegallery','unite_skin':'default','unite_strippanel_enable':'1','unite_panel_position':'left','unite_thumb_width':'90','unite_thumb_height':'112','unite_arrows':'1','unite_fullscreen':'1','unite_fullwindow':'1','unite_zoom_panel':'1','unite_play':'1','unite_play_interval':'5000','unite_pause_on_mouseover':'0','unite_controls_always_on':'1'}>";

					$link = '';
					/* IF value is array and more then on */
					if(is_array($value)&&  count($value) > 0)
					{
						for($i = 0; $i < count($value); $i++)
						{
							$link .= "<li class='product-thumb icon-image-li'>
<a href='$dir/$key/$value[$i]'>
<img src = '$dir/$key/$value[$i]'/>
</a>
</li>";
							
							/* Appending img for production discrition */
							/* Because same element can not use */
							/* Product Discription have different div tag and element */
							$subimgdiv .= "<img src='$dir/$key/$value[$i]' alt='' data-image='$dir/$key/$value[$i]' itemprop='image'/>";
							
							
						}
							
					}					
					
					/* Sub image type */
					$subimgdiv .= '</div>';
					
					$this->productimage[$key] = $link;
					$this->subimgketty[$key] = $subimgdiv;
					
				}
				
				/* To show single images */
				
				foreach($this->majorimg as $key => $value)
				{
					$majorlink = "$dir/$key/$value";
					
					$this->majorimg[$key] = $majorlink;
				}
				
				/* Get array values */
				$this->productimage = array_values($this->productimage);
				
				/* For single images */
				$this->majorimg = array_values($this->majorimg);
				//echo "<pre>";
				//print_R($this->majorimg);
				//echo "</pre>";
				//return false;
				//echo "<pre>"		
				$this->subimgketty = array_values($this->subimgketty);
				
				for($i = 0; $i < count($this->data); $i++)
				{
					array_push($this->data[$i],$this->productimage[$i]);				
					$this->data[$i]['primary_image'] = $this->majorimg[$i];
					$this->data[$i]['sub'] = $this->subimgketty[$i];
					
				}
		}
			
			public function ViewAllProductWithDisKetty()
			{
				
				/* Get the data */
				$datad = $this->ReadProductforuserKetty();
				
				$data = $this->data;
				
				
				$discountdata = array();
				/* Get the special price */
				for($i = 0; $i < count($data); $i++)
				{
					/* get the price and special price */
					$price = $data[$i]['price'];
					$special_price = $data[$i]['special_price'];
					
					/* Check if price is greter then speical price */
					if($price >= $special_price)
					{
						/* Calculate the presentage discount */
						$discount = (($price - $special_price)/$price) * 100;							
							
						$icon = '';
						
						if($discount > 0)
						{
							$icon = "<span class='sale'>$discount% Off</span>";
						}
						
						/* Get the label */
						$discountdata[$i] = $icon;
						
						 
					}
					
					/* Push that value in data */
					$this->data[$i]['discount'] = $discountdata[$i];
					
					
					
				}
				
				
			}
		
			public function ViewAllProductKetty()
			{
				
				
				$this->ViewAllProductWithDisKetty();
				
				
				$reg = '/{{&(.*?)}}/';
				$html = '<li class="item  first free" itemscope itemtype="//schema.org/product">
				<div class="wrapper-hover">
					<div class="product-image-container custom-img"> 
						<a href="product-details?&id={{&_id}}&sku={{&sku}}" title="Flared jeans" class="product-image" itemprop="url">
							<img id="product-collection-image-89" 
							src="{{&primary_image}}" alt="Flared jeans" itemprop="image"/>
						</a>
						
<ul class="product-thumbs icon-image-ul">
{{&0}}
</ul>  
</div>
<div class="product-info">
<h3 class="product-name noSwipe" itemprop="name"><a href="flared-jeans.html" title="Flared jeans)">{{&productname}}</a></h3>
<div class="price-box">
<p class="old-price">
<span class="price-label">Regular Price:</span>
<span class="price" id="old-price-89-widget-new-grid">
${{&price}}.00</span>

</p>

<p class="special-price">
<span class="price-label">Special Price</span>
<span class="price" id="product-price-89-widget-new-grid">
${{&special_price}}.00 </span>

</p>

</div>
<div class="wrapper-relative">
<div class="wrapper-hover-hiden">
<div class="ratings">
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:90%">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
<span class="amount"><a href="#" onclick="var t = opener ? opener.window : window; t.location.href="review/product/list/id/89/"; return false;">2 Review(s)</a></span>
</div>
<div class="actions">

<a href = "ajax/add-to-cart.php?&id={{&_id}}&sku={{&sku}}" onclick = "return false;"><button type="button" title="Add to Cart" class="button btn-cart noSwipe" onclick="setLocation("flared-jeans.html")"><span><span><i class="material-design-shopping232"></i>Add to Cart</span></span></button></a>
<ul class="add-to-links noSwipe">
<li><a href="wishlist/index/add/product/89/form_key/X9d832tDhlX5pYqB/" class="link-wishlist">Add to Wishlist</a></li>
<li><span class="separator">|</span> <a href="catalog/product_compare/add/product/89/uenc/aHR0cDovL2xpdmVkZW1vMDAudGVtcGxhdGUtaGVscC5jb20vbWFnZW50b181MzYzOC8,/form_key/X9d832tDhlX5pYqB/" class="link-compare">Add to Compare</a></li>
</ul>
</div>
</div>
</div>
</div>
<div class="label-product">
<span class="new">New</span> 
{{&discount}}
</div>


</div>
</li>
';
$div = '';
$div .= $this->getTemplate($html, $this->data,$reg);

		return $div;	
		}
		
			public function ProductDetailsKetty($id, $sku)
			{
				/* Check weather getting id or sku */
				if(isset($_GET[$id]) && isset($_GET[$sku]))
				{
					$gid = htmlspecialchars($_GET[$id]);
					$gsku = htmlspecialchars($_GET[$sku]);
				
					/* Run the method for reading product */
					$this->ReadProductforuserKetty();
					
					$idfound = false;
						/* Check id */
					/* Loop each date */
					for($i = 0; $i < count($this->data); $i++)
					{
						
						if($this->data[$i]['_id'] == $gid && $this->data[$i]['sku'] == $gsku)
						{
							$idfound = true;
							/* Check their sku */
							break; 
						}
						
					}
					
					
					$matcheddata   = '';
					/* Get the data */
					if($idfound === true)
					{
						$matcheddata  = $this->data[$i];							
						/* Get html element */							
					}						
					
					return $matcheddata ;				
				}
				
				return false;
			}			
			
			public function ViewProductDetails($id, $sku){

					/* Run the previouse method */
					$PD = $this->ProductDetailsKetty($id,$sku);
					$PD = array($PD);
				
				/* Method must not return false */
				if($PD == false)
				{
						/* Throw the error */
					return false;
				}
				
				/* We got the data */
				
				$reg = '/{{&(.*?)}}/';
				
				/* Getting additional index */
				$avaibility = $this->getTemplate('{{&avaibility}}',$PD,$reg);
				
				/* Get the color put your color here */
				$colors = $this->getProductColors($id,$sku);
				
				/* Check if */
				
				/* Get the sizes */
				$sizes = $this->getProductSizes($id,$sku);
				
				/* Get the prodcut  color for cart update html element  */				
				$colorhtml = $this->getProductColorsInSelectElement($id,$sku);
		
				/* Get the product button collor */
				$productbtncolor = $this->getProductBtnColorsInSelectElement($id,$sku);
				
				/* Get the sizes in html element to send http request for cart */
				$getsizesinhtml = $this->getProductSizesSelectElement($id,$sku);
				
				switch($avaibility)
				
				{
					case '0':
					$avaibility = 'Out of stock';
					break;
					
					case '1':
					$avaibility = 'In stock';
					break;
					
					default:
					break;
				}
				/* Html element */
				$html = '<div class="product-essential row">
<form action="checkout/cart/add/uenc/aHR0cDovL2xpdmVkZW1vMDAudGVtcGxhdGUtaGVscC5jb20vbWFnZW50b181MzYzOC9mbGFyZWQtamVhbnMuaHRtbA,,/product/89/form_key/vCNiKA3dLnAxFDoN/" method="post" id="product_addtocart_form" enctype="multipart/form-data">
<input name="form_key" type="hidden" value="vCNiKA3dLnAxFDoN"/>
<div class="no-display">
<input type="hidden" name="product" value="89"/>
<input type="hidden" name="related_product" id="related-products-field" value=""/>
</div>
<div class="product-img-box col-xs-12  col-lg-8 col-md-6">
<div class="product-name" itemprop="name">
<h1>{{&productname}}</h1>
</div>
{{&sub}}
<!-- Will hode color html code this element -->
<textarea id = "htmlcolorcode" style = "visibility: hidden;">'.$colorhtml.'</textarea>
<textarea id = "htmlbtncolorcode" style = "visibility: hidden;">'.$productbtncolor.'</textarea>
<textarea id = "htmlsizescode" style = "visibility: hidden;">'.$getsizesinhtml.'</textarea>

<input type = "hidden" id = "p_i_p_d" value = "{{&primary_image}}" />
<script type="text/javascript">
    $j(document).on("product-media-loaded", function() {
        ConfigurableMediaImages.init("base_image");
                ConfigurableMediaImages.setImageFallback(89, $j.parseJSON("{"option_labels":{"blue":{"configurable_product":{"small_image":null,"base_image":null},"products":["90"]},"grey":{"configurable_product":{"small_image":null,"base_image":null},"products":["91","92"]},"s":{"configurable_product":{"small_image":null,"base_image":null},"products":["90"]},"l":{"configurable_product":{"small_image":null,"base_image":null},"products":["91"]},"xl":{"configurable_product":{"small_image":null,"base_image":null},"products":["92"]}},"small_image":[],"base_image":{"90":"http:\/\/livedemo00.template-help.com\/magento_53638\/media\/catalog\/product\/cache\/1\/image\/1800x\/040ec09b1e35df139433887a97daa66f\/f\/l\/flared_jeans_5_1.jpg","91":"http:\/\/livedemo00.template-help.com\/magento_53638\/media\/catalog\/product\/cache\/1\/image\/1800x\/040ec09b1e35df139433887a97daa66f\/f\/l\/flared_jeans_1.jpg","92":"http:\/\/livedemo00.template-help.com\/magento_53638\/media\/catalog\/product\/cache\/1\/image\/1800x\/040ec09b1e35df139433887a97daa66f\/f\/l\/flared_jeans_7.jpg","89":"http:\/\/livedemo00.template-help.com\/magento_53638\/media\/catalog\/product\/cache\/1\/image\/1800x\/040ec09b1e35df139433887a97daa66f\/f\/l\/flared_jeans_3.jpg"}}"));
                $j(document).trigger("configurable-media-images-init", ConfigurableMediaImages);
    });
</script>
</div>
<div class="col-xs-12 col-lg-4 col-md-6">
<div class="product-shop">
 
 
<div class="additional-info">
<div class="product-sku">Product Code: <span class="sku-number">{{&sku}}</span></div>
<p class="availability in-stock">
<span class="label">Availability:</span>
<span class="value">'.$avaibility.'</span>
</p>
<div class="availability-only">
<p>
<span title="Only left">Only <strong>{{&available_items}} </strong> left</span>
</p>
</div>
<script type="text/javascript">
    //<![CDATA[
//    $("//").observe("click", function(event){
//        this.toggleClassName("expanded");
//        $("//").toggleClassName("no-display");
//        event.stop();
//        decorateTable("//");
//    });
    //]]>
    </script>
<div class="clear"></div>
</div>
<div class="product-name secondary" itemprop="name">
<span class="h1">{{&productname}}</span>
<input type = "hidden" id = "p_name" value = "{{&productname}}" />
</div>
<div class="extra-info">
<div class="ratings">
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:90%">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
<span class="separator temp">|</span>
<p class="rating-links">
<a href="flared-jeans.html#customer-reviews" class="moveToTab" onclick="">2 Review(s)</a>
<span class="separator">|</span>
<a href="review/product/list/id/89/#review-form">Add Your Review</a>
</p>
</div>
<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery("a.moveToTab").click(function(e){
            moveToTab();
        })
    });

    function moveToTab(){
        jQuery("dt, dd", "#collateral-tabs").each(function(){
            jQuery(this).removeClass("current");
        });
        jQuery(".product-collateral .toggle-tabs li").each(function(){
            jQuery(this).removeClass("current");
            jQuery("#reviews", this).parents("li").click();
        })
        jQuery("html, body").animate({
            scrollTop: jQuery(".product-collateral").offset().top - 80}, "fast"
        );
    }
</script> </div>
<div class="price-info" itemscope itemtype="//schema.org/product">
<div class="price-box">
<p class="old-price">
<span class="price-label">Regular Price:</span>
<span class="price" id="old-price-89">
${{&price}}.00 </span>
</p>
<p class="special-price">
<span class="price-label">Special Price</span>
<span class="price" id="product-price-89">
${{&special_price}}.00 </span>
<input type = "hidden" id = "p_sku" value = "{{&sku}}" />
<input type = "hidden" id = "p_id" value = "{{&_id}}" />
</p>
</div>
</div>
<div class="clear"></div>
<div class="product-options" id="product-options-wrapper">
<dl>
<dt class="swatch-attr">
<span id="select_label_color" class="select-label"></span>
</label>
</dt>

<!-- Here we are going to add new color -->
<dd class="clearfix swatch-attr last">
<div class="input-box">
'.$colors.'
</div>
</dd>


<!-- Here we are going to add new color End -->
<dt class="swatch-attr">


<span id="select_label_size" class="select-label"></span>
</label>
</dt>
<dd class="clearfix swatch-attr last">
<div class="input-box">
'.$sizes.'

</dd>
</dl>
<script type="text/javascript">
        var spConfig = new Product.Config({"attributes":{"92":{"id":"92","code":"color","label":"Color","options":[{"id":"4","label":"blue","price":"0","oldPrice":"0","products":["90"]},{"id":"5","label":"grey","price":"0","oldPrice":"0","products":["91","92"]}]},"134":{"id":"134","code":"size","label":"Size","options":[{"id":"7","label":"S","price":"0","oldPrice":"0","products":["90"]},{"id":"9","label":"L","price":"0","oldPrice":"0","products":["91"]},{"id":"10","label":"XL","price":"0","oldPrice":"0","products":["92"]}]}},"template":"$#{price}","basePrice":"70","oldPrice":"85","productId":"89","chooseText":"Choose an Option...","taxConfig":{"includeTax":false,"showIncludeTax":false,"showBothPrices":false,"defaultTax":8.25,"currentTax":0,"inclTaxTitle":"Incl. Tax"}});
    </script>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        var swatchesConfig = new Product.ConfigurableSwatches(spConfig);
    });
</script>
<script type="text/javascript">
//<![CDATA[
var DateOption = Class.create({

    getDaysInMonth: function(month, year)
    {
        var curDate = new Date();
        if (!month) {
            month = curDate.getMonth();
        }
        if (2 == month && !year) { // leap year assumption for unknown year
            return 29;
        }
        if (!year) {
            year = curDate.getFullYear();
        }
        return 32 - new Date(year, month - 1, 32).getDate();
    },

    reloadMonth: function(event)
    {
        var selectEl = event.findElement();
        var idParts = selectEl.id.split("_");
        if (idParts.length != 3) {
            return false;
        }
        var optionIdPrefix = idParts[0] + "_" + idParts[1];
        var month = parseInt($(optionIdPrefix + "_month").value);
        var year = parseInt($(optionIdPrefix + "_year").value);
        var dayEl = $(optionIdPrefix + "_day");

        var days = this.getDaysInMonth(month, year);

        //remove days
        for (var i = dayEl.options.length - 1; i >= 0; i--) {
            if (dayEl.options[i].value > days) {
                dayEl.remove(dayEl.options[i].index);
            }
        }

        // add days
        var lastDay = parseInt(dayEl.options[dayEl.options.length-1].value);
        for (i = lastDay + 1; i <= days; i++) {
            this.addOption(dayEl, i, i);
        }
    },

    addOption: function(select, text, value)
    {
        var option = document.createElement("OPTION");
        option.value = value;
        option.text = text;

        if (select.options.add) {
            select.options.add(option);
        } else {
            select.appendChild(option);
        }
    }
});
dateOption = new DateOption();
//]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    var optionFileUpload = {
        productForm : $("product_addtocart_form"),
        formAction : "",
        formElements : {},
        upload : function(element){
            this.formElements = this.productForm.select("input", "select", "textarea", "button");
            this.removeRequire(element.readAttribute("id").sub("option_", ""));

            template = "<iframe id="upload_target" name="upload_target" style="width:0; height:0; border:0;"><\/iframe>";

            Element.insert($("option_"+element.readAttribute("id").sub("option_", "")+"_uploaded_file"), {after: template});

            this.formAction = this.productForm.action;

            var baseUrl = "catalog/product/upload/";
            var urlExt = "option_id/"+element.readAttribute("id").sub("option_", "");

            this.productForm.action = parseSidUrl(baseUrl, urlExt);
            this.productForm.target = "upload_target";
            this.productForm.submit();
            this.productForm.target = "";
            this.productForm.action = this.formAction;
        },
        removeRequire : function(skipElementId){
            for(var i=0; i<this.formElements.length; i++){
                if (this.formElements[i].readAttribute("id") != "option_"+skipElementId+"_file" && this.formElements[i].type != "button") {
                    this.formElements[i].disabled="disabled";
                }
            }
        },
        addRequire : function(skipElementId){
            for(var i=0; i<this.formElements.length; i++){
                if (this.formElements[i].readAttribute("name") != "options_"+skipElementId+"_file" && this.formElements[i].type != "button") {
                    this.formElements[i].disabled="";
                }
            }
        },
        uploadCallback : function(data){
            this.addRequire(data.optionId);
            $("upload_target").remove();

            if (data.error) {

            } else {
                $("option_"+data.optionId+"_uploaded_file").value = data.fileName;
                $("option_"+data.optionId+"_file").value = "";
                $("option_"+data.optionId+"_file").hide();
                $("option_"+data.optionId+"").hide();
                template = "<div id="option_"+data.optionId+"_file_box"><a href="#"><img src="var/options/"+data.fileName+"" alt=""><\/a><a href="#" onclick="optionFileUpload.removeFile("+data.optionId+")" title="Remove file" \/>Remove file<\/a>";

                Element.insert($("option_"+data.optionId+"_uploaded_file"), {after: template});
            }
        },
        removeFile : function(optionId)
        {
            $("option_"+optionId+"_uploaded_file").value= "";
            $("option_"+optionId+"_file").show();
            $("option_"+optionId+"").show();

            $("option_"+optionId+"_file_box").remove();
        }
    }
    var optionTextCounter = {
        count : function(field,cntfield,maxlimit){
            if (field.value.length > maxlimit){
                field.value = field.value.substring(0, maxlimit);
            } else {
                cntfield.innerHTML = maxlimit - field.value.length;
            }
        }
    }

    Product.Options = Class.create();
    Product.Options.prototype = {
        initialize : function(config) {
            this.config = config;
            this.reloadPrice();
            document.observe("dom:loaded", this.reloadPrice.bind(this));
        },
        reloadPrice : function() {
            var config = this.config;
            var skipIds = [];
            $$("body .product-custom-option").each(function(element){
                var optionId = 0;
                element.name.sub(/[0-9]+/, function(match){
                    optionId = parseInt(match[0], 10);
                });
                if (config[optionId]) {
                    var configOptions = config[optionId];
                    var curConfig = {price: 0};
                    if (element.type == "checkbox" || element.type == "radio") {
                        if (element.checked) {
                            if (typeof configOptions[element.getValue()] != "undefined") {
                                curConfig = configOptions[element.getValue()];
                            }
                        }
                    } else if(element.hasClassName("datetime-picker") && !skipIds.include(optionId)) {
                        dateSelected = true;
                        $$(".product-custom-option[id^="options_" + optionId + ""]").each(function(dt){
                            if (dt.getValue() == "") {
                                dateSelected = false;
                            }
                        });
                        if (dateSelected) {
                            curConfig = configOptions;
                            skipIds[optionId] = optionId;
                        }
                    } else if(element.type == "select-one" || element.type == "select-multiple") {
                        if ("options" in element) {
                            $A(element.options).each(function(selectOption){
                                if ("selected" in selectOption && selectOption.selected) {
                                    if (typeof(configOptions[selectOption.value]) != "undefined") {
                                        curConfig = configOptions[selectOption.value];
                                    }
                                }
                            });
                        }
                    } else {
                        if (element.getValue().strip() != "") {
                            curConfig = configOptions;
                        }
                    }
                    if(element.type == "select-multiple" && ("options" in element)) {
                        $A(element.options).each(function(selectOption) {
                            if (("selected" in selectOption) && typeof(configOptions[selectOption.value]) != "undefined") {
                                if (selectOption.selected) {
                                    curConfig = configOptions[selectOption.value];
                                } else {
                                    curConfig = {price: 0};
                                }
                                optionsPrice.addCustomPrices(optionId + "-" + selectOption.value, curConfig);
                                optionsPrice.reload();
                            }
                        });
                    } else {
                        optionsPrice.addCustomPrices(element.id || optionId, curConfig);
                        optionsPrice.reload();
                    }
                }
            });
        }
    }
    function validateOptionsCallback(elmId, result) {
        var container = $(elmId).up("ul.options-list");
        if (result == "failed") {
            container.removeClassName("validation-passed");
            container.addClassName("validation-failed");
        } else {
            container.removeClassName("validation-failed");
            container.addClassName("validation-passed");
        }
    }
    var opConfig = new Product.Options({"23":{"69":{"price":0,"oldPrice":0,"priceValue":"0.0000","type":"fixed","excludeTax":0,"includeTax":0},"70":{"price":0,"oldPrice":0,"priceValue":"0.0000","type":"fixed","excludeTax":0,"includeTax":0},"71":{"price":0,"oldPrice":0,"priceValue":"0.0000","type":"fixed","excludeTax":0,"includeTax":0},"72":{"price":0,"oldPrice":0,"priceValue":"0.0000","type":"fixed","excludeTax":0,"includeTax":0}}});
    //]]>
    </script>
<dl>
<dt></dt>
<dd class="last">
<div class="input-box">
'.$this->getProductButtonColors($id,$sku).'
</div>
</dd>
</dl>
<script type="text/javascript">
//<![CDATA[
enUS = {"m":{"wide":["January","February","March","April","May","June","July","August","September","October","November","December"],"abbr":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]}}; // en_US locale reference
Calendar._DN = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]; // full day names
Calendar._SDN = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]; // short day names
Calendar._FD = 0; // First day of the week. "0" means display Sunday first, "1" means display Monday first, etc.
Calendar._MN = ["January","February","March","April","May","June","July","August","September","October","November","December"]; // full month names
Calendar._SMN = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]; // short month names
Calendar._am = "AM"; // am/pm
Calendar._pm = "PM";

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "About the calendar";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL. See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "Prev. year (hold for menu)";
Calendar._TT["PREV_MONTH"] = "Prev. month (hold for menu)";
Calendar._TT["GO_TODAY"] = "Go Today";
Calendar._TT["NEXT_MONTH"] = "Next month (hold for menu)";
Calendar._TT["NEXT_YEAR"] = "Next year (hold for menu)";
Calendar._TT["SEL_DATE"] = "Select date";
Calendar._TT["DRAG_TO_MOVE"] = "Drag to move";
Calendar._TT["PART_TODAY"] = " (" + "today" + ")";

// the following is to inform that "%s" is to be the first day of week
Calendar._TT["DAY_FIRST"] = "Display %s first";

// This may be locale-dependent. It specifies the week-end days, as an array
// of comma-separated numbers. The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Close";
Calendar._TT["TODAY"] = "today";
Calendar._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%b %e, %Y";
Calendar._TT["TT_DATE_FORMAT"] = "%B %e, %Y";

Calendar._TT["WK"] = "Week";
Calendar._TT["TIME"] = "Time:";
//]]>
</script>
<p class="required">* Required Fields</p>
</div>
<script type="text/javascript">decorateGeneric($$("#product-options-wrapper dl"), ["last"]);</script>
<div class="product-options-bottom">
<div class="price-box">
<p class="old-price">
<span class="price-label">Regular Price:</span>
<span class="price" id="old-price-89_clone">
${{&price}}.00</span>
</p>
<p class="special-price">
<span class="price-label">Special Price</span>
<span class="price" id="product-price-89_clone">
${{&price}}.00</span>
</p>
</div>
<div class="add-to-cart">
<div class="qty-wrapper">
<label for="qty">Qty:</label>
<input type="text" pattern="\d*" name="qty" id="qty" maxlength="12" value="1" title="Qty" class="input-text qty"/>
</div>
<div class="add-to-cart-buttons">
<button onclick = "sendDataToCart();"type="button" title="Add to Cart" class="button btn-cart" ><span><span>Add to Cart</span></span></button>
<input type = "hidden" value = "{{&price}}" id ="regular_price" />
<input type = "hidden" value = "{{&special_price}}" id ="special_price" />

<input id="update_action" type="hidden" value="ajaxcart/cart/ajaxUpdate"/>
<input type="hidden" name="product_type" value="configurable"/>
<div class="add-message">
<span data-status="in-progress">Adding product to cart.</span>
<span data-status="complete">Product added to cart.</span>
</div>
</div>
<div id="cart_message"></div>
</div>
<ul class="add-to-links">
<li><a href="wishlist/index/add/product/89/form_key/vCNiKA3dLnAxFDoN/" onclick="productAddToCartForm.submitLight(this, this.href); return false;" class="link-wishlist">Add to Wishlist</a></li>
<li><span class="separator">|</span> <a href="catalog/product_compare/add/product/89/uenc/aHR0cDovL2xpdmVkZW1vMDAudGVtcGxhdGUtaGVscC5jb20vbWFnZW50b181MzYzOC9mbGFyZWQtamVhbnMuaHRtbA,,/form_key/vCNiKA3dLnAxFDoN/" class="link-compare">Add to Compare</a></li>
</ul>
</div>
</div>
<div class="add-to-cart-wrapper">
</div>
 
 
 
<script type="text/javascript">
var addthis_product = "mag-1.0";
var addthis_config 	= {
pubid : "unknown"

}
</script>
 
<div class="addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="" addthis:title="" addthis:description="">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f0c254f1302adf8"></script>
 
<div class="clear"></div>
</div>
</form>
<script type="text/javascript">
        //<![CDATA[
            var productAddToCartForm = new VarienForm("product_addtocart_form");
            productAddToCartForm.submit = function(button, url) {
                if (this.validator.validate()) {
                    var form = this.form;
                    var oldUrl = form.action;

                    if (url) {
                       form.action = url;
                    }
                    var e = null;
                    try {
                        this.form.submit();
                    } catch (e) {
                    }
                    this.form.action = oldUrl;
                    if (e) {
                        throw e;
                    }

                    if (button && button != "undefined") {
                        button.disabled = true;
                    }
                }
            }.bind(productAddToCartForm);

            productAddToCartForm.submitLight = function(button, url){
                if(this.validator) {
                    var nv = Validation.methods;
                    delete Validation.methods["required-entry"];
                    delete Validation.methods["validate-one-required"];
                    delete Validation.methods["validate-one-required-by-name"];
                    // Remove custom datetime validators
                    for (var methodName in Validation.methods) {
                        if (methodName.match(/^validate-datetime-.*/i)) {
                            delete Validation.methods[methodName];
                        }
                    }

                    if (this.validator.validate()) {
                        if (url) {
                            this.form.action = url;
                        }
                        this.form.submit();
                    }
                    Object.extend(Validation.methods, nv);
                }
            }.bind(productAddToCartForm);
        //]]>
        </script>
</div>';

/* Get the element */
	$div = $this->getTemplate($html, $PD,$reg);
	
	return $div;
				
			}
			
		
			public function ProductDiscriptionKetty($id, $sku)
			{
				/* Run the previouse method */
					$PD = $this->ProductDetailsKetty($id,$sku);
					$PD = array($PD);
				
					/* Get the color */
					$sizes = $this->getProductColors($id, $sku);
					/* Method must not return false */
					if($PD == false)
					{
						/* Throw the error */
						return false;
					}
				
				$reg  = '/{{&(.*?)}}/';
				$html =  '<div class="product-collateral toggle-content tabs">
<dl id="collateral-tabs" class="collateral-tabs">
<dt class="tab"><span id="description">Description</span></dt>
<dd class="tab-container">
<div class="tab-content"> <h2>Details</h2>
<div class="std"> {{&shortdiscription}} {{&description}}</div>
</div>
</dd>
<dt class="tab"><span id="additional">Additional Information</span></dt>
<dd class="tab-container">
<div class="tab-content"> <h2>Additional Information</h2>
<table class="data-table" id="product-attribute-specs-table">
<col width="25%"/>
<col/>
<tbody>
<tr>
<th class="label">Origin</th>
<td class="data">{{&origin}}</td>
</tr>
<tr>
<th class="label">Material</th>
<td class="data">{{&material}}</td>
</tr>
<tr>
<th class="label">Lining material</th>
<td class="data">{{&lining}}</td>
</tr>
<tr>
<th class="label">Conditions</th>
<td class="data">{{&condition}}</td>
</tr>
<tr>
<th class="label">Collection</th>
<td class="data">{{&collectionu}}</td>
</tr>
<tr>
<th class="label">Style</th>
<td class="data">{{&style}}</td>
</tr>
<tr>
<th class="label">Seazon</th>
<td class="data">{{&season}}</td>
</tr>
<tr>
<th class="label">Item weight, kg</th>
<td class="data">{{&weight}}</td>
</tr>
</tbody>
</table>
<script type="text/javascript">decorateTable("product-attribute-specs-table")</script>
</div>
</dd>
<dt class="tab"><span id="reviews">Reviews</span></dt>
<dd class="tab-container">
<div class="tab-content">
<div class="box-collateral box-reviews" id="customer-reviews">
<div class="review-heading">
<h2>
Customer Reviews <span>2 item(s)</span>
</h2>
</div>
<dl>
<dt>
<a href="review/product/view/id/2/">
We are lucky to have an opportunity to buy qualitative, fashionable and affordable clothes. </a>
</dt>
<dd>
Therefore being fashionable costs a lot of money. But nowadays fashion is not such unavailable as it was a couple of centuries ago. We are lucky to have an opportunity to buy qualitative, fashionable and affordable clothes. So, with the great pleasure we are offering you our goods, and we are sure that only our choices of garments will suit you best. Our product is universal because it suits different customers with different demands. We assure you it is really important, it shows that our good has such capacity as flexibility.
<table class="ratings-table ratings">
<colgroup>
<col class="review-label"/>
<col class="review-value"/>
</colgroup>
<tbody>
<tr>
<th>Quality</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:100%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
<tr>
<th>Price</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:100%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
<tr>
<th>Value</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:100%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<span class="review-meta">
Review by Devid /
(Posted on 8/25/2015) </span>
</dd>
<dt>
<a href="review/product/view/id/1/">
Fashion has always been so temporary and uncertai </a>
</dt>
<dd>
Fashion has always been so temporary and uncertain. You can’t keep up with it. This social phenomenon is very whimsical, thus we as the consumers always try to stay in touch with all the latest fashion tendencies. Obviously there is nothing wrong about it because fashion satisfies our willingness to be attractive. And also fashion is the detector of prosperity and social rank. So, our natural desire to wear fashionable clothes has many reasons such as historical, social and others.
<table class="ratings-table ratings">
<colgroup>
<col class="review-label"/>
<col class="review-value"/>
</colgroup>
<tbody>
<tr>
<th>Quality</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:80%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
<tr>
<th>Price</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:80%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
<tr>
<th>Value</th>
<td>
<div class="rating-box stars">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<div class="rating" style="width:80%;">
<div class="mask">
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
<i class="material-design-bookmark45"></i>
</div>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<span class="review-meta">
Review by Hitch /
(Posted on 8/25/2015) </span>
</dd>
</dl>
<a class="button review-button" href="review/product/list/id/89/#review-form"><span><span>Add Your Review</span></span></a>
</div>
</div>
</dd>
<dt class="tab"><span id="product_tag_list">Tags</span></dt>
<dd class="tab-container">
<div class="tab-content"><div class="box-collateral box-tags">
<h2>Product Tags</h2>
<form id="addTagForm" action="tag/index/save/product/89/uenc/aHR0cDovL2xpdmVkZW1vMDAudGVtcGxhdGUtaGVscC5jb20vbWFnZW50b181MzYzOC9mbGFyZWQtamVhbnMuaHRtbA,,/" method="get">
<div class="form-add">
<label for="productTagName">Add Your Tags:</label>
<div class="input-box">
<input type="text" class="input-text required-entry" name="productTagName" id="productTagName"/>
</div>
<button type="button" title="Add Tags" class="button" onclick="submitTagForm()">
<span>
<span>Add Tags</span>
</span>
</button>
</div>
</form>
<p class="note">Use spaces to separate tags. Use single quotes (") for phrases.</p>
<script type="text/javascript">
    //<![CDATA[
        var addTagFormJs = new VarienForm("addTagForm");
        function submitTagForm(){
            if(addTagFormJs.validator.validate()) {
                addTagFormJs.form.submit();
            }
        }
    //]]>
    </script>
</div>
</div>
</dd>
<dt class="tab"><span id="catalog.product.video">Video</span></dt>
<dd class="tab-container">
<div class="tab-content">
<div class="video-box box-collateral">
<h2>Video</h2>
<div class="box-collateral-content">
<div class="video">
<iframe src="https://www.youtube.com/embed/E_IG2ubeTME?wmode=opaque" id="video-frame" allowfullscreen></iframe>
</div>
</div>
</div>
<script type="text/javascript">
        jQuery("#video-frame").attr("frameborder","0");
    </script>
</div>
</dd>
</dl>
</div>';
//echo "<pre>";
//print_r($PD);
//echo "</pre>";
//return false;
/**/
$div = $this->getTemplate($html, $PD , $reg);

return $div;
			}
	
			public function ProductNameKetty($id,$sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				
				/* Method must not return false */
				if($PD == false)
				{
						/* Throw the error */
					return false;
				}	
				
				$PD = array($PD);
				
				$reg = '/{{&(.*?)}}/';
				
				$html = '{{&productname}}';
				$productname = $this->getTemplate($html, $PD, $reg);
				
				return $productname;
			}
			
			public function getProductColors($id, $sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				/* Set element */
				$element = '{{&colors}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
				
				/* Get the template */
				$colors = $this->getTemplate($element, $PD , $reg);
			
				/* Run the colord */
				$getcolors ='<div class="form-group">				
				<h6 class="block">Select Color:</h6>
				<div class = "col-md-12" id = "selected-color"></div>
				<input type = "hidden" name = "g-color" id = "g-color"/>';
				
				$hexreg = '/^#[\w\d]{6}$/';
				
				$string = $this->getHexaColor($hexreg, $colors,',');
				
				/* Check the string */
				if(strpos($string,','))
				 {
					/* Explode the string */
					$eachcolor = explode(',',$string);
					
					/* Loop and get the color */
					for($i = 0; $i < count($eachcolor); $i++)
					{
						/* Get the color */
					$getcolors .= 	"<div class = 'col-md-1 colors' 
					name = 'colors[]' style = 'background-color:$eachcolor[$i];' 
					onclick = 'getcolors(this);'
					pcolor = '$eachcolor[$i]'></div>";
	
					}
				}elseif(preg_match($hexreg,$string))
				{
					$getcolors .= 	"<div class = 'col-md-1 colors' 
					name = 'colors[]' style = 'background-color:$string;' 
					onclick = 'getcolors(this);'
					pcolor = '$string'></div>";
				}
				else
				{
					$getcolors .= 'No color available';
				}
				
				$getcolors .= '</div>';
				
				return $getcolors;
			}
	
			public function getProductColorsInSelectElement($id, $sku)
			{
				
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				/* Set element */
				$element = '{{&colors}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
				
				/* Get the template */
				$colors = $this->getTemplate($element, $PD , $reg);
				
				$id = $_GET['id'];
				$sku = $_GET['sku'];
				
				/* Run the colord */
				$getcolors ='<div class = "from-group">
	<select class = "form-control custom56" style = "font-size:10px; margin:0; padding:0; background-color:none;" onchange = "changelementcolor(this);" id = "pcolor'.$id.'">
	<option value = "">Color</option>';
				
				$hexreg = '/^#[\w\d]{6}$/';
				
				$string = $this->getHexaColor($hexreg, $colors,',');
				
				/* Check the string */
				if(strpos($string,','))
				 {
					/* Explode the string */
					$eachcolor = explode(',',$string);
					
					/* Loop and get the color */
					for($i = 0; $i < count($eachcolor); $i++)
					{
						/* Get the color 
						<option value = "$eachcolor[$i]" style = "background-color:$eachcolor[$i];"></option>
						*/
					$getcolors .= 	"<option value = '".$eachcolor[$i]."' style = 'background-color:$eachcolor[$i];'></option>";
						
	
					}
				}elseif(preg_match($hexreg,$string))
				{
					/* 
					*/
					$getcolors .= 	"<option value = '$string' style = 'background-color:$string;'></option>";
				}
				else
				{
					$getcolors .= 'No color available';
				}
				
				$getcolors .= '</select></div>';
				
				return $getcolors;
			}
	
			public function getProductBtnColorsInSelectElement($id, $sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				//echo "<pre>";
				//print_R($PD);
				//echo "</pre>";
				//return false;
				/* Set element */
				$element = '{{&buttoncolor}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
				
				/* Get the template */
				$colors = $this->getTemplate($element, $PD , $reg);
			
				/* Run the colord */
				$id = $_GET['id'];
				$sku = $_GET['sku'];
				
				$getcolors ='<div class = "from-group">
	<select class = "form-control custom56" style = "font-size:10px; margin:0; padding:0; background-color:none;" onchange = "changelementbtncolor(this);" id = "pbtncolor'.$id.'">
	<option value = "">Btn Color</option>';
				
				$hexreg = '/^#[\w\d]{6}$/';
				
				$string = $this->getHexaColor($hexreg, $colors,',');
				
				/* Check the string */
				if(strpos($string,','))
				 {
					/* Explode the string */
					$eachcolor = explode(',',$string);
					
					/* Loop and get the color */
					for($i = 0; $i < count($eachcolor); $i++)
					{
						/* Get the color 
						<option value = "$eachcolor[$i]" style = "background-color:$eachcolor[$i];"></option>
						*/
					$getcolors .= 	"<option value = '".$eachcolor[$i]."' style = 'background-color:$eachcolor[$i];'></option>";
						
	
					}
				}elseif(preg_match($hexreg,$string))
				{
					/* 
					*/
					$getcolors .= 	"<option value = '$string' style = 'background-color:$string;'></option>";
				}
				else
				{
					$getcolors .= 'No color available';
				}
				
				$getcolors .= '</select></div>';
				
				return $getcolors;
			}
	
			public function getProductSizesSelectElement($id, $sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				/* Set element */
				$element = '{{&sizes}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
			
				/* Get the template */
				$sizesinserver = array('xs','s','m','l','xl','xxl','xxxl');
		
				/* Form element html 
				<div class = "from-group">
	<select class = "form-control" style = "font-size:10px; margin:0; padding:0;">
		<option value = "">Size</option>
		<option value = ""></option>
		
	</select>
</div>
</div>
				*/
				$id = $_GET[$id];
				$sku = $_GET[$sku];
				
				$html = '<div class = "from-group">
	<select class = "form-control" style = "font-size:10px; margin:0; padding:0;" id = "htmlsizescode'.$id.'">
	<option value = "">Size</option>';
				/* Check size */		
				
				$sizes = $this->getTemplate($element, $PD , $reg);
				
				$checksize = $this->checkSizes($sizes , ',',$sizesinserver);
				
				
				$element = '';
				
				if($checksize == true)
				{
					
					
					if(strpos($sizes, ','))
					{
						
						/* Explod */
						$eachsize = explode(',',$sizes);
						
						
						for($i = 0; $i < count($eachsize); $i++)
						{
							/* <option value = "$sizes">strtoupper($sizes)</option> */
							$html .= "<option value = '$eachsize[$i]'>".strtoupper($eachsize[$i])."</option>";
						}
					
					}elseif(in_array(strtolower($sizes), $sizesinserver))
					{
						
						$html .= "<option value = '$sizes'>".strtoupper($sizes)."</option>";
					}else
					{
						
					$html .= '<option value = "">No Size</option>';
					
					}
				
				}
				
				$html .= '</select></div>';
				
				
				return $html;
			}
		
			public function getProductSizes($id, $sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				/* Set element */
				$element = '{{&sizes}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
			
				/* Get the template */
				$sizesinserver = array('xs','s','m','l','xl','xxl','xxxl');
		
				/* Form element html */
				$html = '<div class="clearfix">
<h6 class="block">Select Size : <span id = "show-user-sel-size"></span></h6>
<input type = "hidden" class = "form-control" value = "" id = "user-size" />';
				/* Check size */		
				
				$sizes = $this->getTemplate($element, $PD , $reg);
				
				$checksize = $this->checkSizes($sizes , ',',$sizesinserver);
				
				
				$element = '';
				
				if($checksize == true)
				{
					
					
					if(strpos($sizes, ','))
					{
						
						/* Explod */
						$eachsize = explode(',',$sizes);
						
						
						for($i = 0; $i < count($eachsize); $i++)
						{
							
							$html .= "<input type='button' class='btn default' value='$eachsize[$i]' onclick = 'getsize(this);'>";
						}
					
					}elseif(in_array(strtolower($sizes), $sizesinserver))
					{
						
						$html .= "<input type='button' class='btn default' value='$sizes' onclick = 'getsize(this);'>";
					}else
					{
						
					$html .= 'No size available.';
					
					}
				
				}
				
				$html .= '</div>';
				
				
				return $html;
			}
		
			/* Get the button color */
			public function getProductButtonColors($id, $sku)
			{
				$PD = $this->ProductDetailsKetty($id,$sku);
				
				/* Set data in array */
				$PD = array($PD);
			
				/* Set element */
				$element = '{{&colors}}';
				
				/* Get regression */
				$reg  = '/{{&(.*?)}}/';
				
				/* Get the template */
				$colors = $this->getTemplate($element, $PD , $reg);
			
				/* Run the colord */
				
				$getcolors = "<div class='clearfix'><h6 class=block'>Select Button Color</h6>
				<div class = 'col-md-12' id = 'selected-btn-color' ></div>
				<input type = 'hidden' id = 'get-user-btn-color' class = 'form-control' />
				<input type = 'hidden' id = 'p-id' class = 'form-control' value = '$id' />
				<input type = 'hidden' id = 'p-sku' class = 'form-control' value = '$sku' />";
				
				$hexreg = '/^#[\w\d]{6}$/';
				
				$string = $this->getHexaColor($hexreg, $colors,',');
				
				/* Check the string */
				if(strpos($string,','))
				 {
					/* Explode the string */
					$eachcolor = explode(',',$string);
					
					/* Loop and get the color */
					for($i = 0; $i < count($eachcolor); $i++)
					{
						/* Get the color */
					$getcolors .= 	"<input type='button' class='btn custom-btn56' style = 'background-color:$eachcolor[$i]' onclick = 'getbuttoncolor(this);' button-color = '$eachcolor[$i]'>";
	
					}
				}elseif(preg_match($hexreg,$string))
				{
					$getcolors .= 	"<input type='button' class='btn custom-btn56' style = 'background-color:$string' onclick = 'getbuttoncolor(this);' button-color = '$string'>";
				}
				else
				{
					$getcolors .= 'No color available';
				}
				
				$getcolors .= '</div>';
				
				return $getcolors;
			}
		
			public function ThemeBlockShowKettyCart()
			{
				$cartrows = $this->KettyShowCart($this->CartName());
				
				/* Check cart */
				if($cartrows == false)
				{
					return '<div class="cart-msg" id="cart-message"><ul class="messages"><li class="notice-msg">						<ul><li>Shopping cart is empty.</li></ul></li><ul></ul></ul></div>';
				}
				
				/* Get the cart total */
				
				$carttotal = $this->KettyCartTotal($this->CartName());
				
				/* Get total qty */
				$reg = '/{{&(.*?)}}/';
				$carttotalamount = $this->getTemplate('{{&carttotalamount}}', $carttotal , $reg);
				
				/* Cart total quentity */
				$carttotalqty = $this->getTemplate('{{&carttotalqty}}', $carttotal , $reg);				
				
				$theme_block = '<div class="page-title">
<h1>Shopping Cart</h1>
</div>
<div class="theme-block">
<div id = "cart-message565"></div>
<!--
<form action="http://livedemo00.template-help.com/magento_53638/checkout/cart/updatePost/" method="post">
-->
<input name="form_key" value="lccionfP4aYJGgaH" type="hidden">
<table id="shopping-cart-table" class="cart-table data-table">
<colgroup><col width="1">
<col width="1">
<col width="1">
<col width="1">
<col width="1">
<col width="1">
</colgroup><thead>
<tr class="first last">
<th rowspan="1">
	<span class="nobr">Product</span></th>
<th rowspan="1">&nbsp;</th>
<th class="cart-price-head" colspan="1">
 
<span class="nobr">Price</span>
 
</th>
<th rowspan="1">
Qty
</th>
	
<th rowspan="1">
Discount
</th>

<th class="cart-total-head" colspan="1"> 
Total
</th>

<th class="cart-total-head" colspan="1"> 
Remove  
</th>

</tr>
</thead>
<tfoot>
<tr class="first last">
<td colspan="50" class="a-right cart-footer-actions last">
 
<button onclick = "alert(); "type="submit" style="visibility:hidden;" value="update_qty" title="Update Shopping Cart" class="button button-secondary btn-update" ><span><span>Update Shopping Cart</span></span></button>

<button value="empty_cart" title="Empty Cart" class="button2 btn-empty" ajaxurl = "http://localhost/newproject3/ajax/empty-cart.php" onclick = "emptyCart(this)"><span><span>Empty Cart</span></span></button>

<button type="submit" name="update_cart_action" value="update_qty" title="Update Shopping Cart" class="button button-secondary btn-update"><span><span>Update Shopping Cart</span></span></button>


<span class="or">or</span>
<button type="button" title="Continue Shopping" class="button btn-continue" onclick="setLocation("http://livedemo00.template-help.com/magento_53638/")"><span><span>Continue Shopping</span></span></button>
<!--[if lt IE 8]>
                                <input type="hidden" id="update_cart_action_container" />
                                <script type="text/javascript">
                                //<![CDATA[
                                    Event.observe(window, "load", function()
                                    {
                                        // Internet Explorer (lt 8) does not support value attribute in button elements
                                        $emptyCartButton = $("empty_cart_button");
                                        $cartActionContainer = $("update_cart_action_container");
                                        if ($emptyCartButton && $cartActionContainer) {
                                            Event.observe($emptyCartButton, "click", function()
                                            {
                                                $emptyCartButton.setAttribute("name", "update_cart_action_temp");
                                                $cartActionContainer.setAttribute("name", "update_cart_action");
                                                $cartActionContainer.setValue("empty_cart");
                                            });
                                        }

                                    });
                                //]]>
                                </script>
                                <![endif]-->
</td>
</tr>
</tfoot>
<tbody>	
'.$cartrows.'
</tbody>
</table>
<script type="text/javascript">decorateTable("shopping-cart-table")</script>
<!--
</form>
-->
<div class="cart-forms">
<form id="discount-coupon-form" action="http://livedemo00.template-help.com/magento_53638/checkout/cart/couponPost/" method="post">
<div class="discount">
<h2>Discount Codes</h2>
<div class="discount-form">
<label for="coupon_code">Code</label>
<input name="remove" id="remove-coupone" value="0" type="hidden">
<div class="field-wrapper">
<input class="input-text" id="coupon_code" name="coupon_code" value="" type="text">
<div class="button-wrapper">
<button type="button" title="Apply" class="button" onclick="discountForm.submit(false)" value="Apply"><span><span>Apply</span></span></button>
</div>
</div>
</div>
</div>
</form>
<script type="text/javascript">
//<![CDATA[
var discountForm = new VarienForm("discount-coupon-form");
discountForm.submit = function (isRemove) {
    if (isRemove) {
        $("coupon_code").removeClassName("required-entry");
        $("remove-coupone").value = "1";
    } else {
        $("coupon_code").addClassName("required-entry");
        $("remove-coupone").value = "0";
    }
    return VarienForm.prototype.submit.bind(discountForm)();
}
//]]>
</script>
<div class="shipping">
<h2>Estimate Shipping and Tax</h2>
<div class="shipping-form">
<form action="http://livedemo00.template-help.com/magento_53638/checkout/cart/estimatePost/" method="post" id="shipping-zip-form">
<p class="shipping-desc">Enter your destination to get a shipping estimate.</p>
<ul class="form-list">
<li class="shipping-country">
<label for="country" class="required"><em>*</em>Country</label>
<div class="input-box">
<select name="country_id" id="country" class="validate-select" title="Country"><option value=""> </option><option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="VG">British Virgin Islands</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo - Brazzaville</option><option value="CD">Congo - Kinshasa</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Côte d’Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard &amp; McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong SAR China</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau SAR China</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar (Burma)</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="KP">North Korea</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territories</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn Islands</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Réunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin</option><option value="PM">Saint Pierre and Miquelon</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">São Tomé and Príncipe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia &amp; South Sandwich Islands</option><option value="KR">South Korea</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="VC">St. Vincent &amp; Grenadines</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US" selected="selected">United States</option><option value="UY">Uruguay</option><option value="UM">U.S. Outlying Islands</option><option value="VI">U.S. Virgin Islands</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select> </div>
</li>
<li class="shipping-region">
<label for="region_id" class="required"><em>*</em>State/Province</label>
<div class="input-box">
<select class="required-entry validate-select" defaultvalue="" id="region_id" name="region_id" title="State/Province" style="">
<option value="">Please select region, state or province</option>
<option title="Alabama" value="1">Alabama</option><option title="Alaska" value="2">Alaska</option><option title="American Samoa" value="3">American Samoa</option><option title="Arizona" value="4">Arizona</option><option title="Arkansas" value="5">Arkansas</option><option title="Armed Forces Africa" value="6">Armed Forces Africa</option><option title="Armed Forces Americas" value="7">Armed Forces Americas</option><option title="Armed Forces Canada" value="8">Armed Forces Canada</option><option title="Armed Forces Europe" value="9">Armed Forces Europe</option><option title="Armed Forces Middle East" value="10">Armed Forces Middle East</option><option title="Armed Forces Pacific" value="11">Armed Forces Pacific</option><option title="California" value="12">California</option><option title="Colorado" value="13">Colorado</option><option title="Connecticut" value="14">Connecticut</option><option title="Delaware" value="15">Delaware</option><option title="District of Columbia" value="16">District of Columbia</option><option title="Federated States Of Micronesia" value="17">Federated States Of Micronesia</option><option title="Florida" value="18">Florida</option><option title="Georgia" value="19">Georgia</option><option title="Guam" value="20">Guam</option><option title="Hawaii" value="21">Hawaii</option><option title="Idaho" value="22">Idaho</option><option title="Illinois" value="23">Illinois</option><option title="Indiana" value="24">Indiana</option><option title="Iowa" value="25">Iowa</option><option title="Kansas" value="26">Kansas</option><option title="Kentucky" value="27">Kentucky</option><option title="Louisiana" value="28">Louisiana</option><option title="Maine" value="29">Maine</option><option title="Marshall Islands" value="30">Marshall Islands</option><option title="Maryland" value="31">Maryland</option><option title="Massachusetts" value="32">Massachusetts</option><option title="Michigan" value="33">Michigan</option><option title="Minnesota" value="34">Minnesota</option><option title="Mississippi" value="35">Mississippi</option><option title="Missouri" value="36">Missouri</option><option title="Montana" value="37">Montana</option><option title="Nebraska" value="38">Nebraska</option><option title="Nevada" value="39">Nevada</option><option title="New Hampshire" value="40">New Hampshire</option><option title="New Jersey" value="41">New Jersey</option><option title="New Mexico" value="42">New Mexico</option><option title="New York" value="43">New York</option><option title="North Carolina" value="44">North Carolina</option><option title="North Dakota" value="45">North Dakota</option><option title="Northern Mariana Islands" value="46">Northern Mariana Islands</option><option title="Ohio" value="47">Ohio</option><option title="Oklahoma" value="48">Oklahoma</option><option title="Oregon" value="49">Oregon</option><option title="Palau" value="50">Palau</option><option title="Pennsylvania" value="51">Pennsylvania</option><option title="Puerto Rico" value="52">Puerto Rico</option><option title="Rhode Island" value="53">Rhode Island</option><option title="South Carolina" value="54">South Carolina</option><option title="South Dakota" value="55">South Dakota</option><option title="Tennessee" value="56">Tennessee</option><option title="Texas" value="57">Texas</option><option title="Utah" value="58">Utah</option><option title="Vermont" value="59">Vermont</option><option title="Virgin Islands" value="60">Virgin Islands</option><option title="Virginia" value="61">Virginia</option><option title="Washington" value="62">Washington</option><option title="West Virginia" value="63">West Virginia</option><option title="Wisconsin" value="64">Wisconsin</option><option title="Wyoming" value="65">Wyoming</option></select>
<script type="text/javascript">
                       //<![CDATA[
                           $("region_id").setAttribute("defaultValue",  "");
                       //]]>
                       </script>
<input id="region" name="region" value="" title="State/Province" class="input-text required-entry" style="display:none;" type="text">
</div>
</li>
<li class="shipping-postcode">
<label for="postcode" class="required"><em>*</em>Zip</label>
<div class="input-box">
<input class="input-text validate-postcode" id="postcode" name="estimate_postcode" value="" type="text">
</div>
</li>
</ul>
<div class="buttons-set">
<button type="button" title="Estimate" onclick="coShippingMethodForm.submit()" class="button">
<span><span>Estimate</span></span>
</button>
</div>
</form>
<script type="text/javascript">
        //<![CDATA[
            new RegionUpdater("country", "region", "region_id", {"config":{"show_all_regions":true,"regions_required":["AT","CA","EE","FI","FR","DE","LV","LT","RO","ES","CH","US"]},"ES":{"130":{"code":"A Coru\u0441a","name":"A Coru\u00f1a"},"131":{"code":"Alava","name":"Alava"},"132":{"code":"Albacete","name":"Albacete"},"133":{"code":"Alicante","name":"Alicante"},"134":{"code":"Almeria","name":"Almeria"},"135":{"code":"Asturias","name":"Asturias"},"136":{"code":"Avila","name":"Avila"},"137":{"code":"Badajoz","name":"Badajoz"},"138":{"code":"Baleares","name":"Baleares"},"139":{"code":"Barcelona","name":"Barcelona"},"140":{"code":"Burgos","name":"Burgos"},"141":{"code":"Caceres","name":"Caceres"},"142":{"code":"Cadiz","name":"Cadiz"},"143":{"code":"Cantabria","name":"Cantabria"},"144":{"code":"Castellon","name":"Castellon"},"145":{"code":"Ceuta","name":"Ceuta"},"146":{"code":"Ciudad Real","name":"Ciudad Real"},"147":{"code":"Cordoba","name":"Cordoba"},"148":{"code":"Cuenca","name":"Cuenca"},"149":{"code":"Girona","name":"Girona"},"150":{"code":"Granada","name":"Granada"},"151":{"code":"Guadalajara","name":"Guadalajara"},"152":{"code":"Guipuzcoa","name":"Guipuzcoa"},"153":{"code":"Huelva","name":"Huelva"},"154":{"code":"Huesca","name":"Huesca"},"155":{"code":"Jaen","name":"Jaen"},"156":{"code":"La Rioja","name":"La Rioja"},"157":{"code":"Las Palmas","name":"Las Palmas"},"158":{"code":"Leon","name":"Leon"},"159":{"code":"Lleida","name":"Lleida"},"160":{"code":"Lugo","name":"Lugo"},"161":{"code":"Madrid","name":"Madrid"},"162":{"code":"Malaga","name":"Malaga"},"163":{"code":"Melilla","name":"Melilla"},"164":{"code":"Murcia","name":"Murcia"},"165":{"code":"Navarra","name":"Navarra"},"166":{"code":"Ourense","name":"Ourense"},"167":{"code":"Palencia","name":"Palencia"},"168":{"code":"Pontevedra","name":"Pontevedra"},"169":{"code":"Salamanca","name":"Salamanca"},"170":{"code":"Santa Cruz de Tenerife","name":"Santa Cruz de Tenerife"},"171":{"code":"Segovia","name":"Segovia"},"172":{"code":"Sevilla","name":"Sevilla"},"173":{"code":"Soria","name":"Soria"},"174":{"code":"Tarragona","name":"Tarragona"},"175":{"code":"Teruel","name":"Teruel"},"176":{"code":"Toledo","name":"Toledo"},"177":{"code":"Valencia","name":"Valencia"},"178":{"code":"Valladolid","name":"Valladolid"},"179":{"code":"Vizcaya","name":"Vizcaya"},"180":{"code":"Zamora","name":"Zamora"},"181":{"code":"Zaragoza","name":"Zaragoza"}},"CH":{"104":{"code":"AG","name":"Aargau"},"106":{"code":"AR","name":"Appenzell Ausserrhoden"},"105":{"code":"AI","name":"Appenzell Innerrhoden"},"108":{"code":"BL","name":"Basel-Landschaft"},"109":{"code":"BS","name":"Basel-Stadt"},"107":{"code":"BE","name":"Bern"},"110":{"code":"FR","name":"Freiburg"},"111":{"code":"GE","name":"Genf"},"112":{"code":"GL","name":"Glarus"},"113":{"code":"GR","name":"Graub\u00fcnden"},"114":{"code":"JU","name":"Jura"},"115":{"code":"LU","name":"Luzern"},"116":{"code":"NE","name":"Neuenburg"},"117":{"code":"NW","name":"Nidwalden"},"118":{"code":"OW","name":"Obwalden"},"120":{"code":"SH","name":"Schaffhausen"},"122":{"code":"SZ","name":"Schwyz"},"121":{"code":"SO","name":"Solothurn"},"119":{"code":"SG","name":"St. Gallen"},"124":{"code":"TI","name":"Tessin"},"123":{"code":"TG","name":"Thurgau"},"125":{"code":"UR","name":"Uri"},"126":{"code":"VD","name":"Waadt"},"127":{"code":"VS","name":"Wallis"},"128":{"code":"ZG","name":"Zug"},"129":{"code":"ZH","name":"Z\u00fcrich"}},"LV":{"471":{"code":"\u0100da\u017eu novads","name":"\u0100da\u017eu novads"},"366":{"code":"Aglonas novads","name":"Aglonas novads"},"367":{"code":"LV-AI","name":"Aizkraukles novads"},"368":{"code":"Aizputes novads","name":"Aizputes novads"},"369":{"code":"Akn\u012bstes novads","name":"Akn\u012bstes novads"},"370":{"code":"Alojas novads","name":"Alojas novads"},"371":{"code":"Alsungas novads","name":"Alsungas novads"},"372":{"code":"LV-AL","name":"Al\u016bksnes novads"},"373":{"code":"Amatas novads","name":"Amatas novads"},"374":{"code":"Apes novads","name":"Apes novads"},"375":{"code":"Auces novads","name":"Auces novads"},"376":{"code":"Bab\u012btes novads","name":"Bab\u012btes novads"},"377":{"code":"Baldones novads","name":"Baldones novads"},"378":{"code":"Baltinavas novads","name":"Baltinavas novads"},"379":{"code":"LV-BL","name":"Balvu novads"},"380":{"code":"LV-BU","name":"Bauskas novads"},"381":{"code":"Bever\u012bnas novads","name":"Bever\u012bnas novads"},"382":{"code":"Broc\u0113nu novads","name":"Broc\u0113nu novads"},"383":{"code":"Burtnieku novads","name":"Burtnieku novads"},"384":{"code":"Carnikavas novads","name":"Carnikavas novads"},"387":{"code":"LV-CE","name":"C\u0113su novads"},"385":{"code":"Cesvaines novads","name":"Cesvaines novads"},"386":{"code":"Ciblas novads","name":"Ciblas novads"},"388":{"code":"Dagdas novads","name":"Dagdas novads"},"355":{"code":"LV-DGV","name":"Daugavpils"},"389":{"code":"LV-DA","name":"Daugavpils novads"},"390":{"code":"LV-DO","name":"Dobeles novads"},"391":{"code":"Dundagas novads","name":"Dundagas novads"},"392":{"code":"Durbes novads","name":"Durbes novads"},"393":{"code":"Engures novads","name":"Engures novads"},"472":{"code":"\u0112rg\u013cu novads","name":"\u0112rg\u013cu novads"},"394":{"code":"Garkalnes novads","name":"Garkalnes novads"},"395":{"code":"Grobi\u0146as novads","name":"Grobi\u0146as novads"},"396":{"code":"LV-GU","name":"Gulbenes novads"},"397":{"code":"Iecavas novads","name":"Iecavas novads"},"398":{"code":"Ik\u0161\u0137iles novads","name":"Ik\u0161\u0137iles novads"},"399":{"code":"Il\u016bkstes novads","name":"Il\u016bkstes novads"},"400":{"code":"In\u010dukalna novads","name":"In\u010dukalna novads"},"401":{"code":"Jaunjelgavas novads","name":"Jaunjelgavas novads"},"402":{"code":"Jaunpiebalgas novads","name":"Jaunpiebalgas novads"},"403":{"code":"Jaunpils novads","name":"Jaunpils novads"},"357":{"code":"J\u0113kabpils","name":"J\u0113kabpils"},"405":{"code":"LV-JK","name":"J\u0113kabpils novads"},"356":{"code":"LV-JEL","name":"Jelgava"},"404":{"code":"LV-JL","name":"Jelgavas novads"},"358":{"code":"LV-JUR","name":"J\u016brmala"},"406":{"code":"Kandavas novads","name":"Kandavas novads"},"412":{"code":"K\u0101rsavas novads","name":"K\u0101rsavas novads"},"473":{"code":"\u0136eguma novads","name":"\u0136eguma novads"},"474":{"code":"\u0136ekavas novads","name":"\u0136ekavas novads"},"407":{"code":"Kokneses novads","name":"Kokneses novads"},"410":{"code":"LV-KR","name":"Kr\u0101slavas novads"},"408":{"code":"Krimuldas novads","name":"Krimuldas novads"},"409":{"code":"Krustpils novads","name":"Krustpils novads"},"411":{"code":"LV-KU","name":"Kuld\u012bgas novads"},"413":{"code":"Lielv\u0101rdes novads","name":"Lielv\u0101rdes novads"},"359":{"code":"LV-LPX","name":"Liep\u0101ja"},"360":{"code":"LV-LE","name":"Liep\u0101jas novads"},"417":{"code":"L\u012bgatnes novads","name":"L\u012bgatnes novads"},"414":{"code":"LV-LM","name":"Limba\u017eu novads"},"418":{"code":"L\u012bv\u0101nu novads","name":"L\u012bv\u0101nu novads"},"415":{"code":"Lub\u0101nas novads","name":"Lub\u0101nas novads"},"416":{"code":"LV-LU","name":"Ludzas novads"},"419":{"code":"LV-MA","name":"Madonas novads"},"421":{"code":"M\u0101lpils novads","name":"M\u0101lpils novads"},"422":{"code":"M\u0101rupes novads","name":"M\u0101rupes novads"},"420":{"code":"Mazsalacas novads","name":"Mazsalacas novads"},"423":{"code":"Nauk\u0161\u0113nu novads","name":"Nauk\u0161\u0113nu novads"},"424":{"code":"Neretas novads","name":"Neretas novads"},"425":{"code":"N\u012bcas novads","name":"N\u012bcas novads"},"426":{"code":"LV-OG","name":"Ogres novads"},"427":{"code":"Olaines novads","name":"Olaines novads"},"428":{"code":"Ozolnieku novads","name":"Ozolnieku novads"},"432":{"code":"P\u0101rgaujas novads","name":"P\u0101rgaujas novads"},"433":{"code":"P\u0101vilostas novads","name":"P\u0101vilostas novads"},"434":{"code":"P\u013cavi\u0146u novads","name":"P\u013cavi\u0146u novads"},"429":{"code":"LV-PR","name":"Prei\u013cu novads"},"430":{"code":"Priekules novads","name":"Priekules novads"},"431":{"code":"Prieku\u013cu novads","name":"Prieku\u013cu novads"},"435":{"code":"Raunas novads","name":"Raunas novads"},"361":{"code":"LV-REZ","name":"R\u0113zekne"},"442":{"code":"LV-RE","name":"R\u0113zeknes novads"},"436":{"code":"Riebi\u0146u novads","name":"Riebi\u0146u novads"},"362":{"code":"LV-RIX","name":"R\u012bga"},"363":{"code":"LV-RI","name":"R\u012bgas novads"},"437":{"code":"Rojas novads","name":"Rojas novads"},"438":{"code":"Ropa\u017eu novads","name":"Ropa\u017eu novads"},"439":{"code":"Rucavas novads","name":"Rucavas novads"},"440":{"code":"Rug\u0101ju novads","name":"Rug\u0101ju novads"},"443":{"code":"R\u016bjienas novads","name":"R\u016bjienas novads"},"441":{"code":"Rund\u0101les novads","name":"Rund\u0101les novads"},"444":{"code":"Salacgr\u012bvas novads","name":"Salacgr\u012bvas novads"},"445":{"code":"Salas novads","name":"Salas novads"},"446":{"code":"Salaspils novads","name":"Salaspils novads"},"447":{"code":"LV-SA","name":"Saldus novads"},"448":{"code":"Saulkrastu novads","name":"Saulkrastu novads"},"455":{"code":"S\u0113jas novads","name":"S\u0113jas novads"},"449":{"code":"Siguldas novads","name":"Siguldas novads"},"451":{"code":"Skr\u012bveru novads","name":"Skr\u012bveru novads"},"450":{"code":"Skrundas novads","name":"Skrundas novads"},"452":{"code":"Smiltenes novads","name":"Smiltenes novads"},"453":{"code":"Stopi\u0146u novads","name":"Stopi\u0146u novads"},"454":{"code":"Stren\u010du novads","name":"Stren\u010du novads"},"456":{"code":"LV-TA","name":"Talsu novads"},"458":{"code":"T\u0113rvetes novads","name":"T\u0113rvetes novads"},"457":{"code":"LV-TU","name":"Tukuma novads"},"459":{"code":"Vai\u0146odes novads","name":"Vai\u0146odes novads"},"460":{"code":"LV-VK","name":"Valkas novads"},"364":{"code":"Valmiera","name":"Valmiera"},"461":{"code":"LV-VM","name":"Valmieras novads"},"462":{"code":"Varak\u013c\u0101nu novads","name":"Varak\u013c\u0101nu novads"},"469":{"code":"V\u0101rkavas novads","name":"V\u0101rkavas novads"},"463":{"code":"Vecpiebalgas novads","name":"Vecpiebalgas novads"},"464":{"code":"Vecumnieku novads","name":"Vecumnieku novads"},"365":{"code":"LV-VEN","name":"Ventspils"},"465":{"code":"LV-VE","name":"Ventspils novads"},"466":{"code":"Vies\u012btes novads","name":"Vies\u012btes novads"},"467":{"code":"Vi\u013cakas novads","name":"Vi\u013cakas novads"},"468":{"code":"Vi\u013c\u0101nu novads","name":"Vi\u013c\u0101nu novads"},"470":{"code":"Zilupes novads","name":"Zilupes novads"}},"FI":{"339":{"code":"Ahvenanmaa","name":"Ahvenanmaa"},"333":{"code":"Etel\u00e4-Karjala","name":"Etel\u00e4-Karjala"},"326":{"code":"Etel\u00e4-Pohjanmaa","name":"Etel\u00e4-Pohjanmaa"},"325":{"code":"Etel\u00e4-Savo","name":"Etel\u00e4-Savo"},"337":{"code":"It\u00e4-Uusimaa","name":"It\u00e4-Uusimaa"},"322":{"code":"Kainuu","name":"Kainuu"},"335":{"code":"Kanta-H\u00e4me","name":"Kanta-H\u00e4me"},"330":{"code":"Keski-Pohjanmaa","name":"Keski-Pohjanmaa"},"331":{"code":"Keski-Suomi","name":"Keski-Suomi"},"338":{"code":"Kymenlaakso","name":"Kymenlaakso"},"320":{"code":"Lappi","name":"Lappi"},"334":{"code":"P\u00e4ij\u00e4t-H\u00e4me","name":"P\u00e4ij\u00e4t-H\u00e4me"},"328":{"code":"Pirkanmaa","name":"Pirkanmaa"},"327":{"code":"Pohjanmaa","name":"Pohjanmaa"},"323":{"code":"Pohjois-Karjala","name":"Pohjois-Karjala"},"321":{"code":"Pohjois-Pohjanmaa","name":"Pohjois-Pohjanmaa"},"324":{"code":"Pohjois-Savo","name":"Pohjois-Savo"},"329":{"code":"Satakunta","name":"Satakunta"},"336":{"code":"Uusimaa","name":"Uusimaa"},"332":{"code":"Varsinais-Suomi","name":"Varsinais-Suomi"}},"FR":{"182":{"code":"1","name":"Ain"},"183":{"code":"2","name":"Aisne"},"184":{"code":"3","name":"Allier"},"185":{"code":"4","name":"Alpes-de-Haute-Provence"},"187":{"code":"6","name":"Alpes-Maritimes"},"188":{"code":"7","name":"Ard\u00e8che"},"189":{"code":"8","name":"Ardennes"},"190":{"code":"9","name":"Ari\u00e8ge"},"191":{"code":"10","name":"Aube"},"192":{"code":"11","name":"Aude"},"193":{"code":"12","name":"Aveyron"},"249":{"code":"67","name":"Bas-Rhin"},"194":{"code":"13","name":"Bouches-du-Rh\u00f4ne"},"195":{"code":"14","name":"Calvados"},"196":{"code":"15","name":"Cantal"},"197":{"code":"16","name":"Charente"},"198":{"code":"17","name":"Charente-Maritime"},"199":{"code":"18","name":"Cher"},"200":{"code":"19","name":"Corr\u00e8ze"},"201":{"code":"2A","name":"Corse-du-Sud"},"203":{"code":"21","name":"C\u00f4te-d"Or"},"204":{"code":"22","name":"C\u00f4tes-d"Armor"},"205":{"code":"23","name":"Creuse"},"261":{"code":"79","name":"Deux-S\u00e8vres"},"206":{"code":"24","name":"Dordogne"},"207":{"code":"25","name":"Doubs"},"208":{"code":"26","name":"Dr\u00f4me"},"273":{"code":"91","name":"Essonne"},"209":{"code":"27","name":"Eure"},"210":{"code":"28","name":"Eure-et-Loir"},"211":{"code":"29","name":"Finist\u00e8re"},"212":{"code":"30","name":"Gard"},"214":{"code":"32","name":"Gers"},"215":{"code":"33","name":"Gironde"},"250":{"code":"68","name":"Haut-Rhin"},"202":{"code":"2B","name":"Haute-Corse"},"213":{"code":"31","name":"Haute-Garonne"},"225":{"code":"43","name":"Haute-Loire"},"234":{"code":"52","name":"Haute-Marne"},"252":{"code":"70","name":"Haute-Sa\u00f4ne"},"256":{"code":"74","name":"Haute-Savoie"},"269":{"code":"87","name":"Haute-Vienne"},"186":{"code":"5","name":"Hautes-Alpes"},"247":{"code":"65","name":"Hautes-Pyr\u00e9n\u00e9es"},"274":{"code":"92","name":"Hauts-de-Seine"},"216":{"code":"34","name":"H\u00e9rault"},"217":{"code":"35","name":"Ille-et-Vilaine"},"218":{"code":"36","name":"Indre"},"219":{"code":"37","name":"Indre-et-Loire"},"220":{"code":"38","name":"Is\u00e8re"},"221":{"code":"39","name":"Jura"},"222":{"code":"40","name":"Landes"},"223":{"code":"41","name":"Loir-et-Cher"},"224":{"code":"42","name":"Loire"},"226":{"code":"44","name":"Loire-Atlantique"},"227":{"code":"45","name":"Loiret"},"228":{"code":"46","name":"Lot"},"229":{"code":"47","name":"Lot-et-Garonne"},"230":{"code":"48","name":"Loz\u00e8re"},"231":{"code":"49","name":"Maine-et-Loire"},"232":{"code":"50","name":"Manche"},"233":{"code":"51","name":"Marne"},"235":{"code":"53","name":"Mayenne"},"236":{"code":"54","name":"Meurthe-et-Moselle"},"237":{"code":"55","name":"Meuse"},"238":{"code":"56","name":"Morbihan"},"239":{"code":"57","name":"Moselle"},"240":{"code":"58","name":"Ni\u00e8vre"},"241":{"code":"59","name":"Nord"},"242":{"code":"60","name":"Oise"},"243":{"code":"61","name":"Orne"},"257":{"code":"75","name":"Paris"},"244":{"code":"62","name":"Pas-de-Calais"},"245":{"code":"63","name":"Puy-de-D\u00f4me"},"246":{"code":"64","name":"Pyr\u00e9n\u00e9es-Atlantiques"},"248":{"code":"66","name":"Pyr\u00e9n\u00e9es-Orientales"},"251":{"code":"69","name":"Rh\u00f4ne"},"253":{"code":"71","name":"Sa\u00f4ne-et-Loire"},"254":{"code":"72","name":"Sarthe"},"255":{"code":"73","name":"Savoie"},"259":{"code":"77","name":"Seine-et-Marne"},"258":{"code":"76","name":"Seine-Maritime"},"275":{"code":"93","name":"Seine-Saint-Denis"},"262":{"code":"80","name":"Somme"},"263":{"code":"81","name":"Tarn"},"264":{"code":"82","name":"Tarn-et-Garonne"},"272":{"code":"90","name":"Territoire-de-Belfort"},"277":{"code":"95","name":"Val-d"Oise"},"276":{"code":"94","name":"Val-de-Marne"},"265":{"code":"83","name":"Var"},"266":{"code":"84","name":"Vaucluse"},"267":{"code":"85","name":"Vend\u00e9e"},"268":{"code":"86","name":"Vienne"},"270":{"code":"88","name":"Vosges"},"271":{"code":"89","name":"Yonne"},"260":{"code":"78","name":"Yvelines"}},"US":{"1":{"code":"AL","name":"Alabama"},"2":{"code":"AK","name":"Alaska"},"3":{"code":"AS","name":"American Samoa"},"4":{"code":"AZ","name":"Arizona"},"5":{"code":"AR","name":"Arkansas"},"6":{"code":"AF","name":"Armed Forces Africa"},"7":{"code":"AA","name":"Armed Forces Americas"},"8":{"code":"AC","name":"Armed Forces Canada"},"9":{"code":"AE","name":"Armed Forces Europe"},"10":{"code":"AM","name":"Armed Forces Middle East"},"11":{"code":"AP","name":"Armed Forces Pacific"},"12":{"code":"CA","name":"California"},"13":{"code":"CO","name":"Colorado"},"14":{"code":"CT","name":"Connecticut"},"15":{"code":"DE","name":"Delaware"},"16":{"code":"DC","name":"District of Columbia"},"17":{"code":"FM","name":"Federated States Of Micronesia"},"18":{"code":"FL","name":"Florida"},"19":{"code":"GA","name":"Georgia"},"20":{"code":"GU","name":"Guam"},"21":{"code":"HI","name":"Hawaii"},"22":{"code":"ID","name":"Idaho"},"23":{"code":"IL","name":"Illinois"},"24":{"code":"IN","name":"Indiana"},"25":{"code":"IA","name":"Iowa"},"26":{"code":"KS","name":"Kansas"},"27":{"code":"KY","name":"Kentucky"},"28":{"code":"LA","name":"Louisiana"},"29":{"code":"ME","name":"Maine"},"30":{"code":"MH","name":"Marshall Islands"},"31":{"code":"MD","name":"Maryland"},"32":{"code":"MA","name":"Massachusetts"},"33":{"code":"MI","name":"Michigan"},"34":{"code":"MN","name":"Minnesota"},"35":{"code":"MS","name":"Mississippi"},"36":{"code":"MO","name":"Missouri"},"37":{"code":"MT","name":"Montana"},"38":{"code":"NE","name":"Nebraska"},"39":{"code":"NV","name":"Nevada"},"40":{"code":"NH","name":"New Hampshire"},"41":{"code":"NJ","name":"New Jersey"},"42":{"code":"NM","name":"New Mexico"},"43":{"code":"NY","name":"New York"},"44":{"code":"NC","name":"North Carolina"},"45":{"code":"ND","name":"North Dakota"},"46":{"code":"MP","name":"Northern Mariana Islands"},"47":{"code":"OH","name":"Ohio"},"48":{"code":"OK","name":"Oklahoma"},"49":{"code":"OR","name":"Oregon"},"50":{"code":"PW","name":"Palau"},"51":{"code":"PA","name":"Pennsylvania"},"52":{"code":"PR","name":"Puerto Rico"},"53":{"code":"RI","name":"Rhode Island"},"54":{"code":"SC","name":"South Carolina"},"55":{"code":"SD","name":"South Dakota"},"56":{"code":"TN","name":"Tennessee"},"57":{"code":"TX","name":"Texas"},"58":{"code":"UT","name":"Utah"},"59":{"code":"VT","name":"Vermont"},"60":{"code":"VI","name":"Virgin Islands"},"61":{"code":"VA","name":"Virginia"},"62":{"code":"WA","name":"Washington"},"63":{"code":"WV","name":"West Virginia"},"64":{"code":"WI","name":"Wisconsin"},"65":{"code":"WY","name":"Wyoming"}},"RO":{"278":{"code":"AB","name":"Alba"},"279":{"code":"AR","name":"Arad"},"280":{"code":"AG","name":"Arge\u015f"},"281":{"code":"BC","name":"Bac\u0103u"},"282":{"code":"BH","name":"Bihor"},"283":{"code":"BN","name":"Bistri\u0163a-N\u0103s\u0103ud"},"284":{"code":"BT","name":"Boto\u015fani"},"286":{"code":"BR","name":"Br\u0103ila"},"285":{"code":"BV","name":"Bra\u015fov"},"287":{"code":"B","name":"Bucure\u015fti"},"288":{"code":"BZ","name":"Buz\u0103u"},"290":{"code":"CL","name":"C\u0103l\u0103ra\u015fi"},"289":{"code":"CS","name":"Cara\u015f-Severin"},"291":{"code":"CJ","name":"Cluj"},"292":{"code":"CT","name":"Constan\u0163a"},"293":{"code":"CV","name":"Covasna"},"294":{"code":"DB","name":"D\u00e2mbovi\u0163a"},"295":{"code":"DJ","name":"Dolj"},"296":{"code":"GL","name":"Gala\u0163i"},"297":{"code":"GR","name":"Giurgiu"},"298":{"code":"GJ","name":"Gorj"},"299":{"code":"HR","name":"Harghita"},"300":{"code":"HD","name":"Hunedoara"},"301":{"code":"IL","name":"Ialomi\u0163a"},"302":{"code":"IS","name":"Ia\u015fi"},"303":{"code":"IF","name":"Ilfov"},"304":{"code":"MM","name":"Maramure\u015f"},"305":{"code":"MH","name":"Mehedin\u0163i"},"306":{"code":"MS","name":"Mure\u015f"},"307":{"code":"NT","name":"Neam\u0163"},"308":{"code":"OT","name":"Olt"},"309":{"code":"PH","name":"Prahova"},"311":{"code":"SJ","name":"S\u0103laj"},"310":{"code":"SM","name":"Satu-Mare"},"312":{"code":"SB","name":"Sibiu"},"313":{"code":"SV","name":"Suceava"},"314":{"code":"TR","name":"Teleorman"},"315":{"code":"TM","name":"Timi\u015f"},"316":{"code":"TL","name":"Tulcea"},"318":{"code":"VL","name":"V\u00e2lcea"},"317":{"code":"VS","name":"Vaslui"},"319":{"code":"VN","name":"Vrancea"}},"CA":{"66":{"code":"AB","name":"Alberta"},"67":{"code":"BC","name":"British Columbia"},"68":{"code":"MB","name":"Manitoba"},"70":{"code":"NB","name":"New Brunswick"},"69":{"code":"NL","name":"Newfoundland and Labrador"},"72":{"code":"NT","name":"Northwest Territories"},"71":{"code":"NS","name":"Nova Scotia"},"73":{"code":"NU","name":"Nunavut"},"74":{"code":"ON","name":"Ontario"},"75":{"code":"PE","name":"Prince Edward Island"},"76":{"code":"QC","name":"Quebec"},"77":{"code":"SK","name":"Saskatchewan"},"78":{"code":"YT","name":"Yukon Territory"}},"LT":{"475":{"code":"LT-AL","name":"Alytaus Apskritis"},"476":{"code":"LT-KU","name":"Kauno Apskritis"},"477":{"code":"LT-KL","name":"Klaip\u0117dos Apskritis"},"478":{"code":"LT-MR","name":"Marijampol\u0117s Apskritis"},"479":{"code":"LT-PN","name":"Panev\u0117\u017eio Apskritis"},"480":{"code":"LT-SA","name":"\u0160iauli\u0173 Apskritis"},"481":{"code":"LT-TA","name":"Taurag\u0117s Apskritis"},"482":{"code":"LT-TE","name":"Tel\u0161i\u0173 Apskritis"},"483":{"code":"LT-UT","name":"Utenos Apskritis"},"484":{"code":"LT-VL","name":"Vilniaus Apskritis"}},"DE":{"80":{"code":"BAW","name":"Baden-W\u00fcrttemberg"},"81":{"code":"BAY","name":"Bayern"},"82":{"code":"BER","name":"Berlin"},"83":{"code":"BRG","name":"Brandenburg"},"84":{"code":"BRE","name":"Bremen"},"85":{"code":"HAM","name":"Hamburg"},"86":{"code":"HES","name":"Hessen"},"87":{"code":"MEC","name":"Mecklenburg-Vorpommern"},"79":{"code":"NDS","name":"Niedersachsen"},"88":{"code":"NRW","name":"Nordrhein-Westfalen"},"89":{"code":"RHE","name":"Rheinland-Pfalz"},"90":{"code":"SAR","name":"Saarland"},"91":{"code":"SAS","name":"Sachsen"},"92":{"code":"SAC","name":"Sachsen-Anhalt"},"93":{"code":"SCN","name":"Schleswig-Holstein"},"94":{"code":"THE","name":"Th\u00fcringen"}},"AT":{"102":{"code":"BL","name":"Burgenland"},"99":{"code":"KN","name":"K\u00e4rnten"},"96":{"code":"NO","name":"Nieder\u00f6sterreich"},"97":{"code":"OO","name":"Ober\u00f6sterreich"},"98":{"code":"SB","name":"Salzburg"},"100":{"code":"ST","name":"Steiermark"},"101":{"code":"TI","name":"Tirol"},"103":{"code":"VB","name":"Vorarlberg"},"95":{"code":"WI","name":"Wien"}},"EE":{"340":{"code":"EE-37","name":"Harjumaa"},"341":{"code":"EE-39","name":"Hiiumaa"},"342":{"code":"EE-44","name":"Ida-Virumaa"},"344":{"code":"EE-51","name":"J\u00e4rvamaa"},"343":{"code":"EE-49","name":"J\u00f5gevamaa"},"346":{"code":"EE-59","name":"L\u00e4\u00e4ne-Virumaa"},"345":{"code":"EE-57","name":"L\u00e4\u00e4nemaa"},"348":{"code":"EE-67","name":"P\u00e4rnumaa"},"347":{"code":"EE-65","name":"P\u00f5lvamaa"},"349":{"code":"EE-70","name":"Raplamaa"},"350":{"code":"EE-74","name":"Saaremaa"},"351":{"code":"EE-78","name":"Tartumaa"},"352":{"code":"EE-82","name":"Valgamaa"},"353":{"code":"EE-84","name":"Viljandimaa"},"354":{"code":"EE-86","name":"V\u00f5rumaa"}}});
        //]]>
        </script>
<script type="text/javascript">
        //<![CDATA[
            var coShippingMethodForm = new VarienForm("shipping-zip-form");
            var countriesWithOptionalZip = ["HK","IE","MO","PA"];

            coShippingMethodForm.submit = function () {
                var country = $F("country");
                var optionalZip = false;

                for (i=0; i < countriesWithOptionalZip.length; i++) {
                    if (countriesWithOptionalZip[i] == country) {
                        optionalZip = true;
                    }
                }
                if (optionalZip) {
                    $("postcode").removeClassName("required-entry");
                }
                else {
                    $("postcode").addClassName("required-entry");
                }
                return VarienForm.prototype.submit.bind(coShippingMethodForm)();
            }
        //]]>
        </script>
</div>
</div>
</div>
<div class="cart-totals-wrapper">
<div class="cart-totals">
<table id="shopping-cart-totals-table">
<colgroup><col>
<col width="1">
</colgroup><tfoot>


<tr>
<td style="" class="a-right" colspan="1">
<strong>Grand Total</strong>
</td>
<td style="" class="a-right">
<strong><span class="price">$'.$carttotalamount.'.00</span></strong>
</td>
</tr>
</tfoot>
<tbody>
<tr>


<td style="" class="a-right" colspan="1">
Subtotal </td>
<td style="" class="a-right">
<span class="price">$'.$carttotalamount.'.00</span> </td>
</tr>



<tr>
<td style="" class="a-right" colspan="1">
<strong>Total Qty</strong>
</td>
<td style="" class="a-right">
<strong><span class="price">'.$carttotalqty.'</span></strong>
</td>
</tr>
'.$this->PayPalAPI($this->CartName()).'

</tfoot>
<tbody>
<tr>
</tbody>
</table>
<ul class="checkout-types bottom">
<li class="method-checkout-cart-methods-onepage-bottom"> <button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout" onclick="window.location="http://livedemo00.template-help.com/magento_53638/checkout/onepage/";"><span><span>Proceed to Checkout</span></span></button>
</li>
<li class="method-checkout-cart-methods-multishipping"><a href="http://livedemo00.template-help.com/magento_53638/checkout/multishipping/" title="Checkout with Multiple Addresses">Checkout with Multiple Addresses</a>
</li>
</ul>
</div>
</div>
<div class="col-xs-12 col-md-8">
</div>
<div class="col-xs-12 col-md-4">
</div>
</div>	
';			
				return $theme_block;
			
			
			}				
			
			public function UpdateCartKetty1($id)
			{
				/* Start the session */
				session_start();

				/* Check session data */
				if(isset($_SESSION[$this->CartName()]) && count($_SESSION[$this->CartName()]) > 0)
				{
					/* Post the all data */
					$id = $_POST[$id];
					/*&qty="+qty+"&id="+getattr+"&buttoncolor="+getbuttoncolor+"&color="+getcolor+"&size="+getsize;*/

					$buttoncolor = $_POST['buttoncolor'];
					$color = $_POST['color'];
					$size = $_POST['size'];
					$qty = $_POST['qty'];

					/* chek if id match */
					$CartSession = $_SESSION[$this->CartName()];

					$match = false;
					for($i =0; $i < count($CartSession); $i++)
					{
						/* Check id matched */
						if($CartSession[$i]['id'] == $id)
						{
							$match = true;
							break;
						}
					}

					/* Session data to upd$date */

					if($match == true)
					{
						/* Data to update */

						$datatoupdate = $CartSession[$i];

						echo "<pre>";
						print_R($datatoupdate);
						echo "</pre>";
					}



					return false;
					echo "<pre>";
					print_R($_SESSION[$this->CartName()]);
					echo "</pre>";
				}
}

			public function UpdateCartKetty($id,$qty)
			{
				session_start();

				/* start object */
				ob_start();
				/* Get the session name */
				$sessionname = $this->CartName();

				/* Check the session session name*/
				if(isset($_SESSION[$sessionname]) &&  isset($_POST[$qty])  && isset($_POST[$id]))
				{
					$gid = $_POST[$id];
					//$gsku = $_GET[$sku];
					$gqty = $_POST[$qty];
					$buttoncolor = $_POST['buttoncolor'];
					$color = $_POST['color'];
					$size = $_POST['size'];

					if($gqty > 100 && $gqty < 1)
					{
						printf("%s \r\n","<div class = 'note alert-danger'><p>Quentity must be less then 100.</p></div>");
						return false;
					}

					$i = 0;			
					$index = '';
					if($_SESSION[$sessionname]  > 0)
					{
						foreach($_SESSION[$sessionname] as $eachitem)
						{
							if($eachitem['id'] == $gid)
							{
								$index = $i;

								$price = $eachitem['price'];

								$regularprice = $eachitem['regularprice'];

								break;
							}

							$i++;
						}

						/* Calculate discount */
						$discount = $gqty * ($regularprice - $price);
						/* Get the string repeat */
						/* Set color in array */
						$colorarray  = $this->repeatString($color, $gqty);

						/* Set size in array */
						$sizearray =  $this->repeatString($size, $gqty);

						/* Set button color in array */
						$buttoncolorarray =   $this->repeatString($buttoncolor, $gqty);

						$_SESSION[$sessionname][$index]['qty'] = $gqty;

						$price = $_SESSION[$sessionname][$index]['price'];

						$_SESSION[$sessionname][$index]['totalamount'] = $gqty * $price;
						$_SESSION[$sessionname][$index]['userbtncolor'] = $buttoncolorarray;
						$_SESSION[$sessionname][$index]['color'] = $colorarray;
						$_SESSION[$sessionname][$index]['size'] = $sizearray;
						$_SESSION[$sessionname][$index]['discount'] = $discount;
						/* Additional info */

						/* Clean buffer */
						ob_clean();

						return "updated";

					}

				}
}

			public function RemoveCartProduct($id, $sku)
			{
		/* Start the session */
		session_start();
		
		if(isset($_GET[$id]) && isset($_GET[$sku]))
		{
			/* Get the */
			$id = $_GET[$id];
			$sku = $_GET[$sku];
			
			$cart = $_SESSION[$this->CartName()];
			
			if(isset($cart) && count($cart) > 0)
			{
				for($i = 0; $i < count($cart); $i++)
				{
					
					if($cart[$i]['id'] == $id)
					{
						break;
					}
				}
				
				if(count($cart) == '1')
				{
					unset($_SESSION[$this->CartName()]);
					
					return 'removed';
				}
				
				unset($_SESSION[$this->CartName()][$i]);				
				
				sort($_SESSION[$this->CartName()]);		
				
				return "removed";
			}
		}
	}
		
			public function CalCulationOfRevanue()
			{
		/* Get the connecton */
		$mysqli= parent::MysqliSever1();
		/*
		SELECT * FROM papal_checkout_complete_7add4023a86ad WHERE payment_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);
		*/
if(!$sql = $mysqli->query("select SUM(mc_gross) as vi, DATE_FORMAT(payment_date, '%Y-%m' ) as dat from  papal_checkout_complete_7add4023a86ad group by DATE_FORMAT(payment_date, '%Y-%m') order by DATE_FORMAT(payment_date, '%Y-%m') ASC"))
		{
			printf("%s \r\n",$mysqli->error);
		}
		
		$graphData = '';
		
		while($result = $sql->fetch_object())
		{
			$getdate = $result->dat;
			$EMR = $result->vi;
			 
			//$EMR = number_format($EMR,0);
			
			$graphData .= "['$getdate', $EMR],";
			//echo $result->dat." ".$result->vi.'<br/>';
		}
		
		$graphData = substr($graphData, 0,-1);
		
		
		/*
					['2013-01', 4],
					['02/2013', 8],
					['03/2013', 100],
		*/
		/* Close connection */
		$mysqli->close();
		
		return  $graphData;
	}
			
			public function OrderView()
			{
				/* Get the connection */
				$mysqli = parent::MysqliSever1();
				
				/* Set table name */
				$tablename = 'papal_checkout_complete_7add4023a86ad';
				
				/* Run the query */
				if(!$sql = $mysqli->query("SELECT id,payment_status,payment_date,first_name,last_name,mc_gross,mc_currency from $tablename"))
				{
					return printf("%s \r\n",$this->ErroMsg($mysqli->error));
					
				}
				
				/* Fetch the data */
				if($sql->num_rows < 1)
				{
					return printf("%s \r\n",$this->ErroMsg("No order record found."));
				} 
				
				/* Set date in array */
				$records = array();
				/* Feth required data */
				
				/* Count select rows */
				$countRecord = $sql->num_rows;
				
				$this->OrderRecordNum = $countRecord;
				
				
				
				while($result = $sql->fetch_object())
				{					
					/* Set in variable */				
					$id = $result->id;					
					$payment_date = $result->payment_date;
					$first_name = $result->first_name;
					$last_name = $result->last_name;
					$mc_gross = $result->mc_gross;
					$mc_currency = $result->mc_currency;
					$mc_currency_symbol = '$';
					$payment_status = $result->payment_status;
					
					$payment_date = date("F j, Y, g:i a",strtotime($payment_date));
					
					$orderMicrotime = strtotime($payment_date) * 1000;
					
					$status = '<span class="label label-sm label-'.$payment_status.'">'.$payment_status.'</span>';	
					
					$checkbox = '<input type="checkbox" name="id[]" value="'.$id.'">';
					
					$view = '<a href="ecommerce_orders_view.php?id='.$id.'&order_date='.$orderMicrotime.'" class="btn btn-sm btn-circle btn-default btn-editable iframe"><i class="fa fa-search"></i> View</a>';
					
					$records[] = array(
      '<input type="checkbox" name="id[]" value="'.$id.'">',
     $id,  
      $payment_date,
      $first_name.' '.$last_name,
       $first_name.' '.$last_name,
      $mc_currency_symbol.' '.$mc_gross.' '.$mc_currency,
      $mc_currency_symbol.' '.$mc_gross.' '.$mc_currency,
      '<span class="label label-sm label-success">'.$payment_status.'</span>',
      '<a href="ecommerce_orders_view.php?id='.$id.'&payment_date='.$orderMicrotime.'" class="btn btn-sm btn-circle btn-default btn-editable iframe"><i class="fa fa-search"></i> View</a>',
    );
				
					
				}
				
				//echo "<pre>";
				//print_R($records["data"]);
				//echo "</pre>";
				
				/* Close the connection */
				$mysqli->close();
				
				return $records;
			}
			
			/* Show the order details */
			public function ShowOrderDetails($id, $order_date_microtime)
			{
				
				/* Check if request get */
				if(isset($_GET[$id]) && isset($_GET[$order_date_microtime]))
				{
					
					/* Get the details */
					$id  = $_GET[$id];
					$order_date = $_GET[$order_date_microtime];
					
					/* Conver microtime to human readable date */
					$order_date = date('Y-m-d', ($order_date/1000));
					
					/* Get the connection */
					$mysqli = parent::MysqliSever1();
					
					$id = $mysqli->real_escape_string($id);
					//$order_date = $mysqli->real_escape_string($order_date);
					
					$tablename = 'papal_checkout_complete_7add4023a86ad';
					
					if(!$sql = $mysqli->query("select * from $tablename where  id = '$id'"))
					{
						return printf("%s \r\n",$this->ErroMsg($mysqli->error));
					}
					
					/* Check number rows */
					if($sql->num_rows !== 1)
					{
						return false;
						
					}
					
					/* Get the result */
					$getresult = '';
					/* Fetch the date */
					while($result = $sql->fetch_object())
					{
						$getresult = $result;
						
					}
					
					settype($getresult,'array');
										
					/* Close the connection */
					$mysqli->close();
					
					/* Return the date in array */
					return ($getresult);
				}
				else
				{
					return false;
				}
				
				
			}
		
			public function ViewOrderDetails($id, $order_date_microtime)
			{
				$order_details = $this->ShowOrderDetails($id, $order_date_microtime);
				
				if($order_details == false)
				{
					ob_get_clean();
					
					header('Location:ecommerce_orders');
				}
				/* Get the date in array */
				$details = $this->ShowOrderDetails($id, $order_date_microtime);
				
				/* Form html element */
				$details = array_filter($details);
				
				/* Get their cart items */
				$num_cart_items = $details['num_cart_items'];
				
				$items = '';
				
				for($i = 1; $i <= $num_cart_items; $i++)
				{	
					$price = $details["mc_gross_$i"]/$details["quantity$i"];
						
					$items .=	'<tr>
							<td>
							<a href="javascript:;"> '.$details["item_name$i"].' </a>
							</td>
							<td>
							<span class="label label-sm label-success"> Available </span></td>
							<td> $'.$price.'{{&mc_currency}} </td>
							<td>$'.$price.' {{&mc_currency}} </td>
							<td> '.$details["quantity$i"].'</td>
							<td>$'.$details["tax$i"].' {{&mc_currency}}</td>
							<td> $'.$details["tax$i"].' {{&mc_currency}}</td>
							<td> $0.00 {{&mc_currency}}</td>
							<td> $'.$details["mc_gross_$i"].' {{&mc_currency}}</td>
							</tr><br/>';
					
				}
				
				
				
				$html = '<div class="tab-pane active" id="tab_1">
<div class="row">
<div class="col-md-6 col-sm-12">
	<div class="portlet yellow-crusta box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Order Details </div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-default btn-sm">
					<i class="fa fa-pencil"></i> Edit </a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row static-info">
				<div class="col-md-5 name"> Order #: </div>
				<div class="col-md-7 value">{{&_id}}
					<span class="label label-info label-sm"> Email confirmation was sent </span>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Order Date & Time: </div>
				<div class="col-md-7 value"> {{&payment_date}}</div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Order Status: </div>
				<div class="col-md-7 value">
					<span class="label label-success">{{&payment_status}} </span>
				</div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Grand Total: </div>
				<div class="col-md-7 value"> ${{&mc_gross}} {{&mc_currency}}</div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Payment Information: </div>
				<div class="col-md-7 value"> Paypal </div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-6 col-sm-12">
	<div class="portlet blue-hoki box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Customer Information </div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-default btn-sm">
					<i class="fa fa-pencil"></i> Edit </a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row static-info">
				<div class="col-md-5 name"> Customer Name: </div>
				<div class="col-md-7 value"> {{&first_name}} {{&last_name}} </div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Email: </div>
				<div class="col-md-7 value"> {{&payer_email}} </div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> State: </div>
				<div class="col-md-7 value"> {{&address_state}}</div>
			</div>
			<div class="row static-info">
				<div class="col-md-5 name"> Phone Number: </div>
				<div class="col-md-7 value"> 12234389 </div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="row">
<div class="col-md-6 col-sm-12">
	<div class="portlet green-meadow box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Billing Address </div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-default btn-sm">
					<i class="fa fa-pencil"></i> Edit </a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row static-info">
				<div class="col-md-12 value"> {{&first_name}} {{&last_name}}
					<br> {{&address_street}}
					<br> {{&address_city}}
					<br> {{&address_state}}
					<br> {{&address_country}}
					<br> T: 123123232
					<br> F: 231231232
					<br> </div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-6 col-sm-12">
	<div class="portlet red-sunglo box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Shipping Address </div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-default btn-sm">
					<i class="fa fa-pencil"></i> Edit </a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="row static-info">
			<div class="col-md-12 value"> {{&first_name}} {{&last_name}}
					<br> {{&address_street}}
					<br> {{&address_city}}
					<br> {{&address_state}}
					<br> {{&address_country}}
					<br> T: 123123232
					<br> F: 231231232
					<br> </div>
				
			</div>
		</div>
	</div>
</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12">
	<div class="portlet grey-cascade box">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Shopping Cart </div>
			<div class="actions">
				<a href="javascript:;" class="btn btn-default btn-sm">
					<i class="fa fa-pencil"></i> Edit </a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped">
					<thead>
						<tr>
							<th> Product </th>
							<th> Item Status </th>
							<th> Original Price </th>
							<th> Price </th>
							<th> Quantity </th>
							<th> Tax Amount </th>
							<th> Tax Percent </th>
							<th> Discount Amount </th>
							<th> Total </th>
						</tr>
					</thead>
					<tbody>
					'.$items.'
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<div class="row">
<div class="col-md-6"> </div>
<div class="col-md-6">
	<div class="well">
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Sub Total: </div>
			<div class="col-md-3 value"> ${{&mc_gross}} {{&mc_currency}} </div>
		</div>
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Shipping: </div>
			<div class="col-md-3 value"> ${{&mc_shipping}} {{&mc_currency}}</div>
		</div>
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Grand Total: </div>
			<div class="col-md-3 value"> ${{&mc_gross}} {{&mc_currency}}</div>
		</div>
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Total Paid: </div>
			<div class="col-md-3 value"> ${{&mc_gross}} {{&mc_currency}}</div>
		</div>
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Total Refunded: </div>
			<div class="col-md-3 value"> $0.00 {{&mc_currency}}</div>
		</div>
		<div class="row static-info align-reverse">
			<div class="col-md-8 name"> Total Due: </div>
			<div class="col-md-3 value"> $0.00 {{&mc_currency}} </div>
		</div>
	</div>
</div>
</div>
</div>
';
				$order_details = array($order_details);
				$regrex = '/{{&(.*?)}}/';
				$htmlelement = $this->getTemplate($html, $order_details,$regrex);
				//echo "<pre>";
				//print_R($details);
				//echo "</pre>";
				return $htmlelement;
				
			}
		
		
			public function GetOrderInXml($id, $order_date_microtime)
			{
				// We'll be outputting a PDF
				header('Content-type: text/xml');
				// It will be called downloaded.pdf
				header('Content-Disposition: attachment; filename="order.xml"');
				
				$_GET['payment_date'] = '1479461524';
				
				$orderdetails = $this->ShowOrderDetails($id, $order_date_microtime);
				
				
				$xmlstring = '<orderdetails>';
				
				//$xmlstring = '<hello>';
				/* Form the string for xml */
				foreach($orderdetails as $key => $value)
				{
					$xmlstring .= "<$key>$value</$key>";
				}
				
				$xmlstring .= '</orderdetails>';
				
				echo $xmlstring;
				
			}
			
			public function GetOrderInCSV($id, $order_date_microtime)
			{
				header('Content-type: text/csv');
				// It will be called downloaded.pdf
				header('Content-Disposition: attachment; filename="order.csv"');
				
				$_GET['payment_date'] = '1479461524';
				
				$orderdetails = $this->ShowOrderDetails($id, $order_date_microtime);
				
				/* Create CSV string */
				$keys = array_keys($orderdetails);
				$values = array_values($orderdetails);
				
				$data = array($keys,$values);

				$fp = fopen('file.csv', 'w');

				foreach($data as $fields)
				{
					fputcsv($fp, $fields);
				}
				
				$read = file_get_contents('file.csv');
				
				echo $read;
				
				fclose($fp);
			}		
				
			public function DownloadLinkXml($id, $order_date_microtime)
			{
				$orderdetails = $this->ShowOrderDetails($id, $order_date_microtime);
				
				$gid = $orderdetails['id'];				
			
				$string = '<a href="http://localhost/newproject3/ajax/getorderinxml.php?id='.$_GET['id'].'&payment_date=null" download> Export to XML</a>';
				
				return $string;
			}
		
			public function  DownloadLinkCSV($id, $order_date_microtime)
			{
				$orderdetails = $this->ShowOrderDetails($id, $order_date_microtime);
				
				$gid = $orderdetails['id'];				
			
				$string = '<a href="http://localhost/newproject3/ajax/getordercsv.php?id='.$_GET['id'].'&payment_date=null" download> Export to CSV</a>';
				
				return $string;
			}
			
			public function  DownloadLinkPDF($id, $order_date_microtime)
			{
				$orderdetails = $this->ShowOrderDetails($id, $order_date_microtime);
				
				$gid = $orderdetails['id'];				
			
				$string = '<a href="http://localhost/newproject3/administrator_d03fb619b3f94efa8b/ecommerce_orders_view_pdf.php?id='.$_GET['id'].'&payment_date=null" > Export to PDF</a>';
				
				return $string;
			}
			
			public function DownloadOrderPDF($id, $order_date_microtime,$html)
			{		
				//echo __DIR__;
				$gid = $_GET['id'];		
				
				//
				
				require_once("../dompdf_config.inc.php");	
				
				
				// We check wether the user is accessing the demo locally
				$local = array("::1", "localhost");
				$is_local = in_array($_SERVER['REMOTE_ADDR'], $local);
				$dompdf = new DOMPDF();
				//$dompdf->load_html_file('<p></p>');
				$dompdf->load_html($html);
				$dompdf->set_paper('a4','portrait');
				$dompdf->render();

				/* If you want password protected */
				//$dompdf->get_canvas()->get_cpdf()->setEncryption("pass", "pass");
				
				$dompdf->stream("invoice.pdf", array("Attachment" =>true));

   
	
	}
		
			
			public function OrderCountRows()
			{
				$this->OrderView();
				
				return $this->OrderRecordNum;
			}
			
			public function ErroMsg($message)
			{
				/* Retrun the data */
				return "<div class='note note-info'>
                                <p>$message </p>'
                            </div>";				
			}
		
			public function ErroMsgDanger($message)
			{
				/* Retrun the data */
				$message = printf("%s \r\n","<div class='alert alert-danger' style = 'text-transform:lowercase;'>
                                $message'
                            </div>");
				
				return $message;
			}
			
			public function KettyRegister()
			{
				ob_start();
				
				/* Post the data */
				$ajax = $_POST['ajax']; 
				$firstname = $_POST['firstname']; 
				$lastname = $_POST['lastname']; 
				$newsletter = $_POST['newsletter'];
				$email = $_POST['email']; 
				$password = $_POST['password']; 
				$passwordsecound = $_POST['passwordsecond'];
				$lincence = $_POST['licence'];
				
				/* Validate all datas */
				$reg = '/^[a-zA-Z ]{1,}$/';
				
				if(!preg_match($reg, $firstname))
				{
					return $this->ErroMsgDanger("Invalid Name $firstname.");
				}
				
				if(!preg_match($reg, $lastname))
				{
					return $this->ErroMsgDanger("Invalid Name $lastname.");
				}
				
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{					
					return $this->ErroMsgDanger("Invalid email address $email.");				
				}
				
				if(strlen($password) < 6)
				{
					return $this->ErroMsgDanger("Invalid password, minimun 6 characters required.");			
				}
				
				if($password !== $passwordsecound)
				{
					return $this->ErroMsgDanger("Invalid password, both password did not matched");			
				}
				
				/* Get the connection */
				$mysqli = parent::MysqliSever1();
				/* Escape allte string */
				$ajax = $mysqli->real_escape_string($ajax); 
				$firstname = $mysqli->real_escape_string($firstname); 
				$lastname = $mysqli->real_escape_string($lastname); 
				$newsletter = $mysqli->real_escape_string($newsletter);
				$email = $mysqli->real_escape_string($email); 
				$password = $mysqli->real_escape_string($password); 				
				$lincence = $mysqli->real_escape_string($lincence);
				
				$fullname = $firstname.' '.$lastname;				
				
				$password = password_hash($password, PASSWORD_DEFAULT);
				
				$currentdate = date('Y-m-d H:i:s');
				
				$tablename = 'users_kettyclothinge_7add4023a86ad';
				/* Close collection */
				
				
				/* Select email address */
			if(!$sql = $mysqli->query("SELECT id from $tablename where email = '$email'"))
				{ 
					return $this->ErroMsgDanger($mysqli->error);
				}		
				
				if($sql->num_rows > 0)
				{
					return ($this->ErroMsgDanger("Email address $email already register."));
				}
			
										
				
				
				if(!$sql = $mysqli->query("INSERT INTO $tablename(fullname,email,password,newsletter,lincence,date)VALUES('$fullname','$email','$password','$newsletter','$lincence','$currentdate')"))
				{
					return $this->ErroMsgDanger($mysqli->error);
				}		
				
				echo $this->ErroMsgDanger($mysqli->error);
				
				$mysqli->close();
				
				ob_clean();
				
				return "confirm";
				
			}
			
			public function SendConfirmationEmail($email,$link,$array_data_to_send)
			{
				/* $email Email to send */
				/* $link link of the page to send */
				/* $array_data_to_send data in array 
				   $array_data_to_send = array('id'=>23,'registration_date'=>date('Y-m-d H:i:s'))
					
				*/
			}
		
	public function GetRandomString($charctersString, $length)
	{


		// String to get random string 
		//$charctersString = 'abcdefghijklm0123456789';

		// Check string type 
		if(gettype($charctersString) != 'string')
		{
			return printf("%s \r\n","Please input string.");

		}
		// String legnt of chr 
		$chrlength = strlen($charctersString);

		// Define the length 
		//$length = 20;

		// Check the length must be integer 
		if(gettype($length) != 'integer')
		{
			return printf("%s \r\n","Length of string to ouput must be string.");
		}

		// Ouput length must be less then total string length 
		if($length > $chrlength)
		{
			return printf("%s \r\n","Ouput length must be less then string length.");

		}

		$randomstring = '';

		for($i = 0; $i < $length; $i++)
		{
			$randomstring .= $charctersString[rand(0, $length)];
		}

		return $randomstring;
	}

}
?>
