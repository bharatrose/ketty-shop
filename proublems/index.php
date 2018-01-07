<?php

// Writing annonymouse class 
interface cart
{
	public function name(string $name);
	public function price(int $price);
	public function items(int $items);
	
}
	
class CartApplication
{
	private $application;
	
	public function SetCart(cart $cart)
	{
		return $this->application = $cart;
	}
	
	public function GetCart():cart
	{
		return $this->application;
	}
}

// Obtain new class 
$cart = new CartApplication;

// Set the anonymous classes 
$cart->SetCart(new class implements cart
{
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
}

);

// Get the all variables 
$name = $cart->GetCart()->name('Shoe 23');
$price = $cart->GetCart()->price(22);
$items = $cart->GetCart()->items(2);

// Another method 

class SecoundCart
{
	// Property defining 
	private $name = 'Shoe New';
	private $price = 12;
	private $items = 1;
	
	public function CartTotal():int
	{
		// Return 
		return 22;
	}
	
	public function Application()
	{
		return (new class ($this->name, $this->price, $this->items) extends SecoundCart{
		
			// Anonymouse method 
			private $app;
			private $price;
			private $items;
			
			public function __construct(string $name, int $price, int $items)
			{
				$this->app = $name;
				$this->price = $price;
				$this->items = $items;
			}
			
			public function GetCart():array
			{
				return ['name' =>$this->app, 'price'=>$this->price, 'items'=>$this->price];
			}
		});
	}
	
	
	
}
	
// Get the new class 
$app = new SecoundCart;

$app->Application()->GetCart()['name'];

// Now we can access private method as well 
class ThirdClass
{
	private $app = null;
	
	
}

// Get the method out 
$app = new ThirdClass;

//echo $app->app;
$gp = function(){ return $this->app;};

// Use call method 
// Even methods are protected you can simply access it
//var_dump($gp->call($app));

// Loading all class dynamically 

spl_autoload_register(function($classname){

	// Explode with /
	if(strpos($classname, '\\'))
	{
		// Explode the string 
		$exp_str = explode('\\', $classname);
		
		// Get the last string 
		$class = end($exp_str);
		
		return require_once("classes/$class.php");
	}
	
	return require_once("classes/$classname.php");
	
	
	});

// Get the new class
use foo\fighter\a as firstclass;

use c as mythirdclass;

//var_dump(new firstclass);
//var_dump(new mythirdclass);

?>