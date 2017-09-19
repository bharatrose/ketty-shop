<?php
/* Testing overloading method */

echo microtime(true);
exit();
class PropertyTest
{
	// Location for overload data 
	private $data = array();
	
	/* Overloading not used on declared properties */
	public $declared = 1;
	
	/* Overlading only used on this when accessed outside the class */
	private $hidden = 2;
	
	public function __set($name, $value)
	{
		echo "Settting $name to $value \r\n";
		
		// Set the class property 
		$this->data[$name] = $value;
	}
	
	/* Get the value */
	public function __get($name)
	{
		// Get the name 
		echo "Getting name $name \r\n";
		
		// check that propery if exists 
		if(array_key_exists($name, $this->data))
		{
			// Set the name to 
			return $this->data[$name];
		}
		
		$trace = debug_backtrace();
		
		// Throw the error 
		trigger_error('Undefined property via __get():'.$name.'in'. $trace[0]['file'].
		'on line' .$trace[0]['line'], E_USER_NOTICE);
		
		return null;
	}
	
	/* As of PHP 5.1.0 */
	public function __isset($name)
	{
		// Set the name 
		echo "Is '$name' set \r\n";
		
		return isset($this->data[$name]);
	}
}


function getTemplae(string $template, array $data, string $regrex)
{
	// Get the array values 
	$array_values = array_values($data);
	
	// If all value is array 
	$IsAllValArray = true;
	
	// Loop the check 
	for($i = 0; $i < count($array_values); $i++)
	{
		if(!is_array($array_values[$i]))
		{
			// Set variable to false 
			$IsAllValArray = false;
			break;
		}
	}
	
	// Check value is not false 
	if($IsAllValArray === false)
	{
		/* Print the page */
		return "Value is not array $i";
	}
	
	// Get the matched 
	$getmatches = '';
	
	// Check how many matches
	 if(preg_match_all($regrex, $template, $matches))
	 {
	 	$getmatches = $matches[1];
	 }
	 
	 // Get the server key
	 $serverkeys = '';
	 
	 // Find the difference 
	 $diffkey = '';
	 
	 // sort the data 
	 sort($data);
	 
	 // Check and count 
	 if(count($data) > 0)
	 {
	 	$serverkeys = array_keys($data[0]);
	 	
	 	// Get the different keys 
	 	$diffkeys = array_diff($getmatches, $serverkeys);
	 }
	 
	 // Check PDF
	 if(!empty($diffkey))
	 {
	 	// Get the index 
	 	$getindex = implode(',', $diffkey);
	 	
	 	return "Undefined index $getindex \r\n";
	 }
	 
	 // Return data
	 $returndata = '';
	 
	 $reg2 = '/{{@(.*?)}}/';
}

class PropertyTest1
{
	protected $link;
	private $host, $username, $password;
	
	public function __construct($host, $username, $password)
	{
		$this->host=  $host;
		$this->username = $username;
		$this->password = $password;
		
		// run connection method 
		$this->connect();
		
	}
	
	public function connect()
	{
		return new pdo($this->host, $this->username, $this->password);
	}
	
	// Sleep and weak up 
	public function __sleep()
	{
		return ['host', 'username', 'password'];
	}
	
	public function __wakeup()
	{
		return $this->connect();
	}
	
	// Generate error 
	public function __toString()
	{
		// Fatal error object cound not converted into string 
		return $this->connect();
	}
	 
	
	
	
}




// Annonymouse class uses 
interface cart
{
	public function name(string $name);
	public function price(int $price);
	public function items(int $items);
}

class CartApplication
{
	private $cartpp;
	// Set the value 
	public function SetCart(cart $cartapplication)
	{
		return $this->cartapp = $cartapplication;
	}
	
	public function GetCart():cart
	{
		return $this->cartapp;
	}
	
	
}

// get the new class 

$obj = new CartApplication;
$obj->SetCart(new class implements cart {
	
		// Define all abstract method by interface 
		public function name(string $name)
		{
			return $name;
		}
		
		public function price(int $price)
		{
			return $price;
		}
		
		public function items(int $items)
		{
			return $items;
		}

});

// Get the all data 
$name = $obj->GetCart()->name('obj');
$price = $obj->GetCart()->price(90);
$items = $obj->GetCart()->items(9);


/* Using different way of annonymouse */


class cartAnother
{
	private $name;
	protected $price;
	
	public function totalAmount()
	{
		return 'totalAmount';
	}
	
	
	public function GetCart()
	{
		return new class($this->name,$this->price) extends cart{
		
			private $name;
			private $price;
			
			public function __construct($name, $price)
			{
				$this->name = $name;
				$this->price = $price;	
			}
			
			public function GetAnnoCar()
			{
				return ['pname' => $this->name,
						'price' => $this->price];
			}
		
		};
	}

	
}

// Get the objecyt 
//$obj = new cart;

//$obj->GetCart()->GetAnnoCar();

/* Practing magic method */
class MagicMethods
{
	protected $link;
	private $host, $username, $password;
	
	private $deb;
	
	// Static method 
	static $instances = 0;
	public $instance;
	
	public function __construct($host, $username, $password,$deb)
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		
		// Return connect method 
		$this->connect();
		$this->deb = $deb;
		
		$this->instance = ++self::$instances;
	}
	
	
	public function connect()
	{
		return new PDO("mysql:host=localhost;dbname=laravel", $this->username, $this->password);
	}

	public function __sleep()
	{
		return ["mysq:host", 'username', 'password'];
	}
	
	public function __wakeup()
	{
		return $this->connect();
	}
	
	// Show som error 
	public function __toString()
	{
		return $this->connect();
	}
	
	
	public function __invoke($x)
	{
		var_dump($x);
	}
	
	public function __debugInfo()
	{
		return [
            'propSquared' => $this->deb ** 2,
        ];
	}
	
	
	public function __clone()
	{
		$this->instance = ++self::$instances;
	}
}

$obj = new MagicMethods('localhost', 'root', 'root', 3);

//var_dump(is_callable($obj));
//echo "<pre>";
//print_R($obj);
//echo "</pre>";
//var_dump($obj);
//$obj2 = clone $obj;

//echo "<pre>";
//print_r($obj2);
//echo "</pre>";

// Writing anonymouse class 
interface chat
{
	public function username (string $username);
	
	
	public function message(array $message);
	
	
	public function MsgDate(string $msgdata);
	
}


class ChatApplication
{

	private $link;
	
	private $password = 'mypassword';
	
	public function SetUser(chat $chat)
	{
		$this->link = $chat;
	}
	
	public function GetUser():chat
	{
		return $this->link;
	}
}


// Set the method
$obj = new ChatApplication;

// Set the object 
$obj->SetUser(new class implements chat{

	public function username(string $username)
	{
		// Return username 
		return $username;
	}
	
	public function message(array $message)
	{
		// Return the message 
		return $message;
	}
	
	public function MsgDate(string $msgdate)
	{
		return $msgdate;
	}
});


// Get the message 
$username = $obj->GetUser()->username('bharatrose1');
$message = $obj->GetUser()->message(['hello','how']);


// Writing anonymouse class again 
$getlink = function()
{
	return $this->password;
};

$getlink->call(new ChatApplication);


// Another way of anonymouse class 

class anotherWay
{
	private $username = 'Dick Head';
	private $password;
	
	public function Cart()
	{
		return 'Cart Application';
	}
	
	public function GetCartDetails()
	{
		return (new class ($this->username) extends anotherWay{
		
			private $username;
			
			public function __construct($username)
			{
				$this->username = $username;
			}
			
			public function GetAnnonym()
			{
				return $this->username.__class__;
			}
		});
	}
}

//$obj = new anotherWay;

//echo $obj->GetCartDetails()->GetAnnonym();


/* Working with magic methods */
class MagicLife
{

	private $host, $username, $password, $dbname;
	
	protected $connection;
	
	// Static method 
	static $instances = 0;
	public $instance;
	
	public function __construct($host, $username, $password, $dbname)
	{
		// Return pdo connection 
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->dbname = $dbname;
		
		// Return the connection 
		$this->connect();
		
		$this->instance = ++self::$instances;
	}
	
	public function connect()
	{
		// Return pdo connection 
		return new PDO("mysql:host=localhost;dbname=laravel", $this->username, $this->password);
	}
	
	public function __clone()
	{
		$this->instance = ++self::$instances;
	}
	
	public function __toString()
	{
		// Covert to to string 
		
	}
	
	public function __wakeup()
	{
		return $this->connect();
	}
	
	public function __sleep()
	{
		return ['host', 'username', 'password', 'dbname'];
	}
	
	
}


// Testing how to access non static method as static 

class UploadFiles
{
	public function func1()
	{
		// This is static method 
		return 'non-static';
	}
	
	public static function func2()
	{
		return (new self)->func1(); 
	}
}

// get the object 
$obj = UploadFiles::func2();
echo $obj;
?>