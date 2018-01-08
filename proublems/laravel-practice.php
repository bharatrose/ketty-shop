<?php
namespace App\Http\Controllers;

/* Need request Classess */
use Illuminate\Http\Request;

/* Using DB Eloquent Class */
use Illuminate\Support\Facades\DB;

/* Using Controller Class */
use App\Http\Controllers\Controller;

/* To receive the request */
use Illuminate\Support\Facades\Redirect;

/* Session Class */
use Illuminate\Pagination\PaginationServiceProvider;

/* User Authentication */
use Illuminate\Support\Facades\Auth;

/* For file uploading */
use App\Http\Requests\UploadRequest;

/* Class for storing data in File system */
use Illuminate\Support\Facades\Storage;

/* Facades for file system */
use Illuminate\Support\Facades\File;

/* For Caching */
use Illuminate\Support\Facades\Cache;

/* For custom pagination */
use Illuminate\Pagination\LengthAwarePaginator;

/* Retriving collection from database */
use Illuminate\Support\Collection;

/* Using database Eloquent */
use App\Ketty;

/* Eloquent for product */
use App\Product;

class KettyController extends controller
{
	public function index(Request $request)
	{
		// Check administrator 
		$admin = Auth::user();
		
		// Check admin email address 
		if($admin['email'] == 'bharatrose1@gmail.com')
		{
			return Redirect::to('Ketty/administrator');
		}
		
		// Per page request 
		$perpage = 5;
		
		// Check if request has page 
		if($request->has('page') && $request->isMethod('get'))
		{
			// Get the all product form the database 
			$maxpage = count($this->ReturnDataAsArray('all-product-cached'));
			
			// Rules to validate data 
			$rules = array('page'=>"required|integer|max:$maxpage, min:1");
			
			// Validate with data 
			$validator = Validator::make(Input::all(), $rules);
			
			// If validator fails 
			if($validator->fails())
			{
				// Redirect to home page 
				return Redirect::to('/')->withErrors($validator);
			}
		}
		
		
		// Check if product get has ordering mechanism
		if($request->has('orderby')
		&& $request->has('page')
		&& $request->isMethod('get')
		&& count($request->all() == 2))
		{
			// Get the order by 
			$sortproduct = $request->get('orderby');
			
			// Get the page 
			$page = $request->get('page');
			
			// Skip from 
			$skipfrom = $page * $perpage;
			
			
			// Check order by value 
			// If statement is faster then switch 
			if($sortproduct == 'price-high-to-low')
			{
				// Get the cached name 
				$cachename = 'product-order-by-price-desc';
				
				// Get the cached data 
				$collect = $this->ReturnDataAsArray($cachename);
				
				// Extract the data 
				$nextpage = $this->ExtractData($page, $perpage, $cahceddata);
				
				// Get the custom pagination 
				$pagination = $this->CustomPagination($cachename);
				
				// Append url to pagination 
				$pagination->appends(['orderby'=>$request->get('orderby'),
									'page' => $pagination->currentPage()])->links();
									
				// Return the data to view 
				return view('Ketty.index', ['users'=>$nextpage, 'result'=>$pagination]);
			
			
			} else if($sortproduct == 'price-low-to-high') {
				
				// Get the cached name 
				$cahcename = 'product-order-by-price-asc';
				
				// Get the array data 
				$collect = $this->ReturnDataAsArray($cachename);
				
				// Extract the data 
				$nextpage = $this->ExtractData($page, $perpage, $collect);
				
				// Create custom pagination 
				$pagination = $this->CustomPagination($cachename);
				
				// Append url in pagination 
				$pagination->appends(['orderby' => $request->get('orderby'),
						'page' => $pagination->currentPage()])->links();
						
				// Return data to view 
				return view("Ketty.view", ['users' => $nextpage, 'result' => $pagination])
			}
		}
	}
	
	
	public function ExtractData($page, $perpage, $collect)
	{
		// Get per page 
		$page = $page - 1;
		
		// Skip data from 
		$skipfrom = $page * $perpage;
		
		// Data will skip from collection 
		$perpage = array_slice($collect, $skipfrom);
		
		// Show the remaning data 
		$nextpage = array_slice($perpage, 0, 5);
		
		// Return next page 
		return $nextpage;
		
		
	}
	
	public function ReturnPagination($collection)
	{
		// Get the current page 
		$currentpage = LengthAwarePaginator::resolveCurrentPage();
		
		// Create new collection with data in array 
		$getcol = new Collection($collection);
		
		// Data per pagination 
		$perpage = 5;
		
		// Per page from cached data 
		$perPage = 5;
		
		// Slice the collection to get the items to display in current
		// Page 
		$currentPageSearchResult = $getcol->slice($currentPage * $perPage, $perPage)->all();
		
		
		// Number of pagination shows in view 
		$paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResult, count($getcol), $perpage);
		
		
		// Chnage pagination path 
		$paginatedSearchResults->withPath('http:://localhost/laravelnew/public/ketty');
		
		// Return pagination search result 
		return $paginatedSearchResults;
	}
	
	/* Pagination on the base of Cached data */
	public function CustomPagination($cachename)
	{
		// Get the collection from cached data 
		$collect = $this->ReturnDataAsArray($cachename);
		
		// Get the instance to create pagination
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		
		
		// Create new pagination on the base of Cached data 
		$paginatedSearchResults = $this->ReturnPagination($collect);
		
		// Return pagination 
		return $paginatedSearchResults;
	}
	
	/* Get all cached data */
	public function GetAllCachedData(Request $request, $cachename)
	{
		// Get the pagination by cache name 
		$paginatedSearchResults = $this->CustomPagination($cachename);
		
		// Data per page 
		$perpage = 5;
		
		
		// Collect that as array 
		/* Becase data is stored as Std Object Class */
		$collect = $this->ReturnDataAsArray($cachename);
		
		
		// Check the page request only 
		if($request->has('page')
		&& count($request->all()) == 1
		&& $request->isMethod('get'))
		{
			// Get the page 
			$page = $request->get('page');
			
			// Reduce the page by 1
			$page = $page -1 ;
			
			// Skip data from 
			$skipfrom = $page * $perpage;
			
			// Get the sliced data from cached data 
			$perpage = array_slice($collect, $skipfrom);
			
			// Show remaning data to vie 
			$newpage = array_slice($perpage, 0, 5);
			
			// Return the data to age
			return view('Ketty.index')->with()['users' => $nextpage, 'result'=> $paginatedSearchResults];
		} else {
			
			// Slice the array nad how first $perpage 
			$perpage = array_slice($collect, 0, $perpage);
			
			return view('Ketty.index')->with(['users' => $perpage, 'result' => $paginatedSearchResults]);
		}
		
		return false;
	}	
	
	public function show($id)
	{
		// Get the cached cache 
		$cachekey = 'all-product-cahced';
		
		// Get cached data by key 
		$cachedData = Cache::get($cachekey);
		
		// Get the index number of array column 
		$index = $this->ReturnIndexICD($cachedData, $id);
		
		// Check if index is not false 
		if($index == false)
		{
			return Redirect::to('Ketty')->withErrors("Product was unable to found.\r\n");
			
		}
		
		// Reduce the index by 1
		$index = $index - 1;
		
		// Return the data to view 
		return view('Ketty.show')->with(['users'=>$cachedData[$index]]);
	}
	
	public function ReturnIndexICD($cacheddata, $id)
	{
		$i = 1;
		
		// Set the variable to false 
		$pfound = false;
		
		// Loop each data 
		foreach($cachedData as $key => $value)
		{
			// Get the id 
			$cid = $value->id;
			
			// Check if id exists 
			 if(($cid <=> $id) = 0)
			 {
			 	$pfound = true;
			 	
			 	// Break 
			 	break;
			 }
		}
		
		// Check the variable 
		if($pfound == true)
		{
			return $i;
		}
		
		return false;
	}
	
	public function create()
	{
		// Return view 
		return view('Ketty.create');
	}
	
	/* File uploading method */
	public function store(UploadRequest, $request)
	{
		// Defining rules 
		$rules = [
					'name'=> 'required',
					'email' => 'required|email|unique:user,email',
					'password' => 'required',
					'confirm_password' => 'required|same:password',
					'phone' => 'required|integer',
					'remember_token' => 'N/A',
					'price' => 'required|integer|max:50000'
				];
				
	// Using Validator Facades 
	$validator = Validator::make(Input::all(), $rules);
	
	// If validation fails 
	if($validator->fails())
	{
		// Return Redirection 
		return Redirect::to('Ketty/create')->withErrors($validator)->withInput(Input::except('password'))
	}
	
	// Simply store the file 
	$name = Input::get('name');
	$email = Input::get('email');
	$password = Input::get('password');
	$phone = Input::get('phone');
	$price = Input::get('price');
	
	
	// Has the password 
	$password = password_hash($password, PASSWORD_BCRYPT);
	
	
	// Get the file name 
	$dbfilename = [];
	
	// Loop each request 
	foreach($request->photos as $photo)
	{
		// Get the original filename 
		$filename = $photo->getClientOriginalName();
		
		// Set the random number 
		$number = rand(1, 100000);
		
		// Explode filename with 
		// We can do in 
		/*
			
		*/
		
		$explodefilename = explode('.', $filename);
		
		// New filename 
		$newfilename = round(microtime(true).$number. '.' .end($explodefilename));
		
		// Move th the image to directory 
		$photo->move('product-images/', $newfilename);
		
		// Append dbfilename to variable 
		$dbfilename[] = $newfilename;
	}
	
	// Encode the file 
	$jsonfilesname = json_encode($dbfilename);
	
	// Insert data to database 
	$insert = DB::table('users')->insert(
								[
								'name' => $name,
								'email' => $email,
								'password' => $password,
								'create_at' => $created_at
								]
								);
								
	// Flesh messsage to session 
	Session::flash("message", "sucessfully create $name");
	
	// Set all data in cached 
	$this->CachedAllData();
	
	// Redirect to create 
	return Redirect::to('Ketty/create');
	
	}
	
	public function CacheAllData()
	{
		// Get the all data from db
		$all_product = DB::table('users')->get();
		
		// Fetch product by price DESC
		$fpbpd = DB::table('users')->orderBy('price', 'desc')->get();
		
		
		// Get product by asc price 
		$fpbpa = DB::table('users')->orderBy('price', 'asc')->get();
		
		// Defining cache keyes 
		$cachekey = 'all-product-cached';
		
		
		// Cache key for desc 
		$CPOBPA = 'product-order-by-price-desc';
		
		// Cache product order by asc 
		$CPOPASC = 'product-order-by-price-asc';
		
		
		// Set all product to cached 
		$this->SetCacheData($cachekey, $all_product);
		
		// Set cache by price desc
		$this->SetCacheData($CPOBA, $fpbpd);
		
		
		// Set cache by price asc
		$this->SetCacheData($CPOPASC, $fpbpa);
		
		// Return true 
		return true;
	}
	
	// Get all product in array 
	// Because object is give 
	public function getAllProductInArray()
	{
		// Get the product 
		$all_product = DB::table('users')->get();
		
		// Return the data 
		return $all_product;
	}
	
	
	// Setting cache data 
	public function SetCacheData($key, $data)
	{
		// If cache key exists 
		if(Cache::has($key))
		{
			Cache::forget($key);
			
			// Set Raw value 
			Cache::forever($key, $data)
		
		} else {
			
			Cache::forever($key, $data);
		}
	}
	
 	// Caching data as memcached 
 	public function SetCacheDataAsMemeCache($key, $data)
 	{
 		// Check if key exists 
 		if(Cache::store('memcached')->has($key))
 		{
 				// Then remove the data 
 				Cache::store('memcached')->forget($key);
 				
 				// Store new data 
 				Cache::store('memcached')->forever($key, $data);
 		
 		} else {
 				
 				// Set the cache value by key 
 				Cache::store('memcached')->forever($key, $data);
 		}
 	}
 	
 	// Returning cached data as array 
 	public function ReturnCachedDataAsArray($cachedname)
 	{
 		// Get the all product 
 		$all_product = Cache::get($cachedname);
 		
 		// Get the collection from cached key
 		$collection = new Collection($all_product);

		// Get the collected items 
		$collect = $collection->items;
		
		// Return the items 
		return $collect;
 	}
 	
 	// Destroy record by id 
 	public function destroy($id)
 	{
 		// First get the id 
 		$users = DB::table('users')->select('filename')->where('id', '=', $id)->get();
 		
 		
 		// Loop each files name 
 		foreach($users as $get)
 		{
 			$filename = $get->filename;
 		}
 		
 		// Decode json encoded string 
 		// If secound params is true then array or object 
 		$decodeJson = json_decode($filename, true);
 		
 		// Get the public directory 
 		$directory = public_path().'/product-images/';
 		
 		// File link 
 		$Flink = '';
 		
 		// Loop each images 
 		if($decodeJson != false)
 		{
 			// Loop each data 
 			foreach($decodeJson as $key => $value)
 			{
 				// Remember mysql 5.7.8 version only 
 				// Support native json data type 
 				$Flink = $directory.$value;
 				
 				// Remove the files 
 				if(!File::delete($Flink))
 				{
 					// Session flash the message 
 					session::flash('message', "Image was unable to remove. \r\n");
 					
 					// Redirect to home 
 					return Redirect::to('Ketty');
 				}
 				
 			}
 		}
 		
 		// Delete the data by id 
 		DB::table('users')->where('id', '=', $id)->delete();
 		
 		// Recached all data 
 		$this->CacheAllData();
 		
 		// Flash session data 
 		Session::flash("message", "Product with id $id is deleted. \r\n");
 		
 		// Redirect to keyy
 		return Redirect::to('Ketty');
 		
 	}
 	
 	public function edit($id)
 	{
 		// Find the record in database 
 		$find = DB::table('users')->where('id', '=', $id)->find($id);
 		
 		// Return view with all datas 
 		return view('Ketty.edit')->with(['users' => $find])->withInput(Input::except('password'));
 		
 		
 	}
 	
 	public function update(UploadRequest $request, $id)
 	{
 		// Set data in array for validation 
 		$rules = [
 					'name' => 'required',
 					'email' => 'required|email',
 					'password' => 'required',
 					'confirm_password' => 'required|same:password',
 					'phone' => 'required|numeric'
 				];
 				
 		// Validator 
 		$validator = Validator::make(Input::all(), $rules);
 		
 		// Check validator is not false 
 		if($validator->fails())
 		{
 			return Redict::to('Ketty/create')->withErrors($validator)->withInput(Input::except('password'));
 			
 		} else {
 			
 			$users = DB::table('users')->select('filename')->where('id', '=', $id)->get();
 			
 			
 			// Loop each data 
 			foreach($user as $get)
 			{
 				$filename = $get->filename;
 			}
 			
 			// Json decode the data 
 			$decodeJson = json_decode($filename, true);
 			
 			// Setting directory 
 			$directory = public_path().'/product-images/';
 			
 			// File link 
 			$Flink = '';
 			
 			// If variable is not false 
 			if($decodeJson != false)
 			{
 				foreach($decodeJson as $key => $value)
 				{
 					$Flink = $directory.$value;
 					
 					// Find the directory 
 					if(!File::delete($Flink))
 					{
 						Session::flash("Following file was unable to delete $Flink.");
 						
 						return Redirect::to('Ketty');
 					}
 				}
 			}
 		
 		
 			// Get the request 
 			$name = Input::get('name');
 			$email = Input::get('email');
 			$password = Input::get('password');
 			$phone = Input::get('phone');
 			$created_at = date('Y-m-d H:i:s');
 			$updated_at = $created_at;
 			
 			
 			// Has Password php 7 password hasing 
 			// Secound params is not allowed 
 			$password = password_hash($password);
 			
 			
 			// Database file names 
 			$dbfilename = [];
 			
 			foreach($request->photo as $photo)
 			{
 				// Get the original file name 
 				$filename = $photo->getClientOriginalName();
 				
 				// Get random number 
 				$number = rand(1, 1000);
 				
 				// Explode filename with .
 				$explodefilename = explode('.', $filename);
 				
 				// Set new filename 
 				$newfilename = round(microtime(true)) .$number. '.'. end($explodefilename);
 				
 				// Upload the file to server 
 				$photo->move('product-images', $newfilename);
 				
 				// Append filename in variable 
 				$dbfilename[] = $newfilename;
 			}
 			
 			// Encode file name in json string 
 			$jsonfilesname = json_encode($dbfilename);
 			
 			// Update the data with id 
 			$update = DB::table('users')->where('id', '=', $id)
 			->update([
 						'name' => $name,
 						'email' => $email,
 						'password' => $password,
 						
 					]);
 			
 		// Cache all data 
 		$this->CacheAllData();
 		
 		// Flash message 
 		Session::flash('message', 'Successfully updated !.');
 		
 		// Redirect 
 		return Redirect::to("Ketty");
 		
 		
 		}
 	}
 	
 	public function error()
 	{
 		// Return login view
 		return view ("Ketty.login");
 	}
 	
 	// Register 
 	public function register()
 	{
 		return view('Ketty.register');
 	}
 	
 	// Reqyest Reset Passpass
 	public function requestRestPass()
 	{
 		return view('Ketty.passwords.email');
 	}
 	
 	public function AdminTest()
 	{
 		// Get all product 
 		$product = Product::all();
 		
 		
 		// Return to administrator 
 		return View('Ketty.administrator')->with('products', $product);
 	}
 	
 	
 	/* Making cart */
 	public function makecart(Request $request)
 	{
 	
 		// Check if requst is update cart 
 		if($request->has('update_cart'))
 		{
 			// Get the product id to update 
 			$pid = Input::get("item-id");
 			
 			// Get the quentity 
 			$qty = Input::get("quantity");
 			
 			// Set validation data in array 
 			$rules = [
 						'quantity' => 'required | numerice |max:100'
 					];
 		
 		// Validator 
 		$validator = Validator::make(Input::all(), $rules);
 		
 		// Check if validator fails 
 		if($validator->fails())
 		{
 			// Redirect with error 
 			return Redirect::to('Ketty/showcart')->withErrors($validator);
 		}
 		
 		$i = 0;
 		
 		// Update the session data 
 		foreach(session($this->CartSession()) as $eachItem)
 		{
 			// Loop each data 
 			while(list($key, $value) = each($eachItem))
 			{
 				if($key == 'pid' && $value = $pid)
 				{
 					// New quantity 
 					$newamount = $eachItems["price"] * $qty;
 					
 					
 					// Set set new quentity by it's index 
 					Session::put("cart_session.$i.qty", $qty);
 					
 					// Set the session data with new amount 
 					Session::put("cart_session.$i.totalamount",$newamount);
 					
 					// Break the loop 
 					break; 
 				}
 			}
 			
 			// Increase the index 
 			$i++;
 		}
 		
 		// Send the redirection 
 		return Redirect::to("Ketty/showcart")->withMessage("Cart is updated.");
 		
 		} else if($request->has("delete-cart")) {
 			
 			// Get the pid 
 			$pid = Input::get("item-id");
 			
 			// Get the product index index in session 
 			
 			$pindex = Input::get('pindex');
 			
 			// Chage string type index to ingeter 
 			$itype = settype($pindex, 'integer');
 			
 			// Get the session data 
 			$getdata = session('cart_session');
 			
 			// Get the product name 
 			$pname = $getdata[$pindex]['pname'];
 			
 			// Unset the data 
 			unset($getdata[$pindex]);
 			
 			// sort the data 
 			sort($getdata);
 			
 			// Set new value to session 
 			session(['cart_session' => $getdata]);
 			
 			// Return the dat 
 			return Redirect::to("Ketty/showcart")->withMessage("Product $name is removed from cart.")
 			
 			// Line 916 Completed from Git coad
 			
 			// Get the input element 
 			$id = Input::get('id');
 			$qty = Input::get('qty');
 			$images = Input::get('images');
 			$price = Input::get('price');
 			$name = Input::get('name');
 			
 			// Get the total amount 
 			$totalamount = $qty * $price;
 			
 			// form array containing data 
 			$sessionData = [
 							'pid' => $id,
 							'qty' => $qty,
 							'images' => $images,
 							'price' => $price,
 							'pname' => $name,
 							'totalamount' => $totalamount
 							];
 			
 		// check if session is not set 
 		if(!Session::has($this->CartSession()))
		{
			session([$this->CartSession() => [0=> $sessionData]])
			
			// return the data 
			return Redirect::to("Ketty/$id")->withMessage("Product $name was sucessfully added to cart.");
			
		
		} 	
		
		// Set the same product 
		$sameproduct = false;
		
		// Set the index 
		$i = 0;
				
 		// Cart session 
 		$cart = session('cart_session');
 		
 		
 		// Get the cart session 
 		$cartsession = Session::get('cart_session');
 		
 		// Loop each session data 
 		foreach(session($this->CartSession()) as $eachItem)
 		{
 			while(list($key, $value) = each($eachItem))
 			{
 				// check key and value 
 				if($key == 'pid' && $value = $id)
 				{
 					// set new quentity 
 					$newitem = $eachItem['qty'] + $qty;
 					
 					// Set the new total amount 
 					$newamount = $eachItem['totalamount'] + $totalamount;
 					
 					// set the session
 					Session::put("cart_session.$i.$qty", $newitem);
 					
 					// Set new amount 
 					Session::put("cart_session.$i.totalamount", $newamount);
 					
 					// Set the  variable to true 
 					$samproduct = true;
 					
 					// Break the loop 
 					break;
 					
 				}
 			}
 			
 			// Increate $i
 			$i++;
 		}	
 		
 		// If its not same product 
 		if($samproduct == false)
 		{
 			$request->session()->push('cart_session', $sessionData);
 			
 			// Redirect to the vie 
 			return Redirect::to("Ketty/$id")->withMessage("Product is added.");
 			
 		} else {
 		
 			return Redirect::to("Ketty/$id")->withMessage("Product is added.");
 			
 		}
 		
 		
 		}
 	}
 	
 	/* Setting cart session */
 	public function CartSession()
 	{
 		return 'cart_session';
 	}
 	
 	public function showcart(Request $request)
 	{
 		// Count the session data 
 		$countsession = count(session('cart_session'));
 		
 		// Check if not session set 
 		if($countsession < 1 ||
 			$request->session()->has('cart_session') != true)
 		{
 			// Return with message 
 			return Redirect::to("Ketty")->withMessage("Your carti is empty !.");
 			
 		}
 		
 		// Get the session data 
 		$sessiondata = session('cart_session');
 		
 		// Total qty 
 		$totalqty = '';
 		
 		// Total amount 
 		$totalamount = '';
 		
 		// Check the session 
 		if($request->session()->has('cart_session'))
 		{
 			// In alternative case you can use 
 			$totalqty = array_sum(array_map(function($item) { 
    		return $item['qty']; 
			}, $this->CartSession()));

			// Get total amount 
			$totalamount = array_sum(array_map(function($item) { 
    		return $item['amount']; 
			}, $this->CartSession()));
			
 			// Loop each session data 
 			foreach(session($this->CartSession()) as $eachItem)
 			{
 				// Get the total quentity 
 				$totalqty += $eachItem['qty'];
 				
 				// total amount 
 				$totalamount += $eachItem['totalamount'];
 				
 				
 			}
 			
 			// Get the paypal buttom as well
 			$payment = $this->PayPalAPI($request);
 			
 			
 			// set the variable value and index in array 
 			$total = array(
 							'totalqty' => $totalqty,
 							'totalamount' => $totalamount,
 							'CC' => '$',
 							'payment' => $payment
 							);
 		}
 		
 		// Return  view 
 		return view('Ketty.showcart',['usercart' => $sessionData. 'total' => $total]);
 		
 		
 		// 1068 Line number
 	}
 	
 	public function carttotal()
 	{
 		// Set total qty 
 		$totalqty = '';
 		
 		// Set the total amount 
 		$totalamount = '';
 		
 		// check if request is exists 
 		if($request->session()->has('cart_session'))
 		{
 		
 			
 			// Loop each data session 
 			foreach(session($this->CartSession()) as $eachItem)
 			{
 				// |Ttotal quentity 
 				$totalqty += $eachItem['qty'];
 				
 				// Total amount 
 				$totalamount += $eachItem['totalamount'];
 			}
 			
 			/* In alternateive we can get */
 			/*
 			$totalquty = array_sum(array_map(function($items){
 					return $items['qty'];
 						},$this->CartSession()));
 			*/
 			
 			/* Get the total amount */
 			/*
 			$totalamount = array_sum(array_map(function($items){},$this->CartSession()));
 			*/
 			
 			$total = ['totalqty' => $totalqty, 'totalamount' => $totalamount];
 			
 			// Return the vairable 
 			return $total;
 		}
 	}
 	
 	public function emptyCart()
 	{
 		return view('Ketty.empty_cart');
 	}
 	
 	/* Set paypal api */
 	public function PayPalAPI(Request, $request)
 	{
 		// Coun cart session data 
 		$countsession = count(session('cart_session'));
 		
 		/* Check session count more then 0 */
 		if($countsession > 0 || $request->session()->has('cart_session') == true)
 		{
 			/* Set the paypal checkout buttom */
 			$pp_checkout_btn = "";
 			
 			/* User must be logged in */
 			
 			/* Check if user is logged in */
 			
 			/* Get user login facades */
 			
 			$ifuserlogin = Auth::check();
 			
 			/* Check the variable */
 			if($ifuserlogin !== true)
 			{
 				/* Set login button */
 				$pp_checkout_btn = '<a href = "login" class="btn btn-success"><i class = "fa fa-sign-in"></i> Login</a>';
 			
 				/* Return the button */
 				return $pp_checkout_btn;
 			}
 			
 			/* Or Else form paypal checkout button */
 			$pp_checkout_btn = '';
 			
 			
 			/* Set the elements */
 			$pp_checkout_btn .= '<input type = "hidden" name = "cmd" value = "cart"/>
 				<input type = "hidden" name = "upload" value = "1" />
 				<input type = "hidden" name = "business" value = "'.$this->PaypalEmail.'"/>
 			';
 			
 			// Set the index 
 			$i = 0;
 			
 			/* Loop each items */
 			foreach(session('cart_session') as $eachItem)
 			{
 				/* Increase the i by 1 */
 				$x = $i + 1;
 				
 				/* Product name */
 				$productname = $eachItem["name"];
 				
 				/* Product price */
 				$price = $eachItem['price'];
 				
 				/* Get the quentity */
 				$qty = $eachItem['qty'];
 				
 				/* Product id */
 				$pid = $eachItem['pid'];
 				
 				/* Paypal checkout button */
 				$pp_checkout_btn = 
 				'<input type = "hidden" name = "item_number' . $x . '" value= "'.$pid.'"/>
 				<input type = "hidden" name = "item_name' . $x . '" value= "'.$productname.'"/>
 				<input type = "hidden" name = "amount_' . $x . '" value= "'.$price.'"/>
 				<input type = "hidden" name = "quentity_' . $x . '" value= "'.$qty.'"/>
 				';
 				
 				/* Increase i by */
 				$i++;
 			}
 			
 			/* Setting paypal variable */
 			
 			$pp_checkout_btn .= 
 			'<input type = "hidden" name = "notify_url" value = "'.$this->p_notify_url.'" />
 			<input type = "hidden" name = "return" value = "'.$this->p_return.'"/>
 			<input type = "hidden" name = "rm" value = "2"/>
 			<input type = "hidden" name = "cbt" value = "'.$this->p_cbt.'"/>
 			<input type = "hidden" name = "cancel_return" value = "'.$this->p_cancel_return.'"/>
 			<input type = "hidden" name = "Ic" value = "'.$this->p_Ic.'"/>
 			<input type = "hidden" name = "currency_code" value = "USD"/>
 			<button  value="XXX"  name="submit" alt="Make Payments with Paypal" class = "button btn-continue" /><span><span><i class = "fa fa-paypal"></i> Paypal</span></span></button>
 			
 			';
 			
 			/* Return checkout button */
 			return $pp_checkout_btn;
 			
 		  }
 	} 
 	
 	/* Paypal checkout complete */
 	public function paypalcheckoutcomplete(Request $reuqest)
 	{
 		/* PHP 7*/
 		$ispost = isMethod('post') ?? null;
 		
 		/* Check if variable is not null */
 		if($ispost !== null)
 		{
 			// Get the payment status 
 			$payment_status = Input::get('payment_status') ?? null;
 			
 			/* Check if varible is not null */
 			if($payment_status !== null)
 			{
 				// Receive all data 
 				$data = Input::all();
 				
 				// Txn id 
 				$txn_id = $data['txn_id'];
 				
 				// Qeury 
 				$query = DB::table('purchaseorder')->where('data->txn_id', $txn_id)->get();
 				
 				
 				// Must contain txn id 
 				if(count($query) !== 0)
 				{
 					// Send the redirection 
 					return Redirect::to('Ketty/payment/paypal_cancle')->
 					withErrors("Txn id injection, could not complet the request.");
 				}
 				
 				// Encode the data to json 
 				$data = json_encode($data);
 				
 				/* Note
 				You need to have new version of mysql 5.7 only support native json data
 				*/
 				// Inser the dat 
 				DB::table('purchaseorder')->insert(['data'=>$data]);
 				
 				// Forget the session 
 				$request->session()->forget('cart_session');
 				
 				// Return to the view 
 				return view('Ketty.payment.papal-checkout-complete')->with(['msg'=> 'Your order is confirm. order id $id.']);
 			}
 		} 
 	}
 	
 	public function administrator()
 	{
 		// Return administrator view
 		 return view('Ketty/administrator/index');
 	}
 	
 	public function searchString()
 	{
 		// Set the array string 
 		$foundsomething = array();
 		
 		// Check if search request exitst 
 		if($request->get('search') == '')
 		{
 			// Redirect to index page 
 			return Redirect::to("Ketty");
 		}
 		
 		// Chek the string 
 		/* You have done wrong here you can use */
 		/* What is called is else if statement */
 		if($request->has('search') && $request->has('_token')
 		&& count($request->all()) === 2)
 		{
 			// Get the search request 
 			$STS = $request->get('search');
 			
 			// Get the cached all data 
 			$gcd = Cache::get($CachKey);
 			
 			// Loop each data 
 			foreach($gcd as $key => $value)
 			{
 				// Get the name 
 				$names = $value->name;
 				
 				// Check if string exists 
 				if(strpos(trim(strtolower($names)), trim(strtolower($STS))) !== false)
 				{
 					// Fond the string 
 					$foundsomething[$key] = $value;
 				}
 			}
 			
 			// Sort the array 
 			sort($foundsomthing);
 			
 			// Get the perpage 
 			$perpage = $this->perPage();
 			
 			// Slice the result 
 			$result = array_slice($foundsomething, 0, $perpage);
 			
 			
 		} else if($request->has('page') && $request->has('_token')
 		&& $request->has('search'))
 		{
 			// Get the reqeust 
 			$STS = $request->get('search');
 			
 			// Get the cached key 
 			$cachkey = 'all-product-cached';
 			
 			// Get the cached all data 
 			$gcd = Cache::get($cachkey);
 			
 			// Search string in array 
 			foreach($gcd as $key => $value)
 			{
 				// Access each name 
 				$names = $value->name;
 				
 				// Check the string if exists 
 				
 				/* You have done wrong here as well */
 				/* Write function for same function which occur */
 				if(strpos(trim(strtolower($names)), trim(strtolower($STS))) !== false)
 				{
 					// Set the found string 
 					$foundsomthing[$key] = $value;
 					
 				}
 				
 				/* Alternative we can use */
 				/*
 				 	$haystack = array (
  					'say hello',
 					 'hello stackoverflow',
  					'hello world',
  					'foo bar bas'
				);

					$matches  = preg_grep ('/^hello (\w+)/i', $haystack);
 				*/
 				
 				
 			}
 			
 			// Sort the array 
 			sort($foundstring);
 			
 			// Get the product per page 
 			$perpage = $this->perPage();
 			
 			
 			// Set the page 
 			$page = $request->get('page');
 			
 			// Extract the data 
 			$result = $this->ExtractData($page, $perpage, $foundsomthing);
 					
 		}
 		
 		// Check found something 
 		if(count($foundsomthing) == 0)
 		{
 			// Redirect to the page 
 			return Redirect::to("Ketty")->withErrors("Could not be able to find someting !.");
 			
 		}
 		
 		// Get the pagination 
 		$paginatedSearchResults = $this->ReturnPagination($foundsomething);
 		
 		// Set the patination 
 		$paginatedSearchResults->appends(['_token' => $request->get('_token'),
 			'search' => $request->get('search'), 'page' => $paginatedSearchResults->currentPage() -1])->links();
 		
 		$paginatedSearchResults->withPath('http://localhost/laravelnew/public/Ketty/search');
 		
 		// Return view with data 
 		return view('Ketty.search')->with(['users' => $result, 'result' => $paginatedSearchResult]);
 		
 		
 		// Line at 1362
 		
 	}
 	
 	public function search()
 	{
 		retur view('Ketty.search');
 	}
 	
 	/* return page */
 	public function perPage()
 	{
 		return 5;
 	}
 	
 	public function EmailTemplate()
 	{
 		return view("Ketty.email-template")
 	}
 	
 	
 	
 	
 	
}
?>