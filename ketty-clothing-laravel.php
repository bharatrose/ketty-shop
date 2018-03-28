<?php

/* Using Name Space */
namespace App\Http\Controllers;

/* Use Request Classes */
use Illuminate\Http\Request;

/* Using DB Class */
use Illuminate\Support\Facades\DB;

/* Using Controller class */
use App\Http\Controllers\Controller;

/* Using Input class to receive the request */
use Illuminate\Support\Facades\Input;

/* Using validator for data validation */
use Illuminate\Support\Facades\Validator;

/* Using Redirection for redirection */
use Illuminate\Support\Facades\Redirect;

/* Session class */
use Illuminate\Support\Facades\Session;

/* Pagination service provider */
use Illuminate\Pagination\PaginationServiceProvider;

/* User Authentication */
use Illuminate\Support\Facades\Auth;

/* For File uploading */
use App\Http\Requests\UploadRequest;

/* Class for storing data in File system  added*/
use Illuminate\Support\Facades\Storage;

/* Facades for file system */
use Illuminate\Support\Facades\File;

/* For Caching */
use Illuminate\Support\Facades\Cache;

/* For custom paginate */
use Illuminate\Pagination\LengthAwarePaginator;

/* Getting collection for the database */
use Illuminate\Support\Collection;

/* Eloquent Ketty */
use App\Ketty;

/* Eloquent for product */
use App\Product;

        
class KettyController extends Controller
{
	/* Defining all paypal variable */
	
	// Paypal related methods 
	protected $PaypalAPIButton;
	
	// Holds paypal html button 
	protected $GetPaypalAPIButton;
	
	// Set paypal Email address
	protected $PaypalEmail = "bharatrose3-facilitator@gmail.com";
	
	// Paypal notify url 
	protected $p_notify_url = "https://localhost/laravelnew/public/Ketty/payment/my_ipn.php";
	
	// Where to return after posting data to paypal
	protected $p_return = "https://localhost/laravelnew/public/Ketty/payment/papal-checkout-complete";
	
	// If cancel by the paypal which page to show 
	protected $p_cbt = "https://localhost/laravelnew/public/Ketty/payment/paypal_cancel";
	
	protected $p_cancel_return = "XXX";
	
	// Which curren to use for payment purpose
	protected $p_Ic = "US";
	
	// Currence code for payment 
	protected $p_currency_code = "USD";
    
	// For currency conversion 
	public $currencyvalue = '1';
	
	// Currency 
	public $currency = "US";
	
	// Currency code 
	public $currencycode = "USD";
	
	// Collected items 
	public $AllCollection;
	
	
	/* Index method */
	public function index(Request $request)
	{
		
	// Get current user who is logged in 
	$admin = Auth::user();

	// Check if that is admin  
	if($admin['email'] == 'bharatrose1@gmail.com')
	{
		// Return direct to home page of admin 
		return Redirect::to('Ketty/administrator');
	}
	
	// DB query need to be different by request 
	// Fore eg is get request orderby=price-low-to-high&page=2
	// Because laravel does not provide any service for this 
	// Feature we need to make is manually 
	// Set the pagination 
	
	// Recrods per page 
	$perpage = 5;
		
	// Check if request contatin page and method is get 
	if( $request->has('page') 
	   && $request->isMethod('get'))
		{
		
		// Get the cached data 
		$maxpage = count($this->ReturnDataAsArray('all-product-cached'));
		
		// Set validation in array  		
		$rules = array(			
			'page' => "required|integer|max:$maxpage,min:1"
		);
	
		// valid the data 
		$validator = Validator::make(Input::all(),$rules);
				
		// Check with fails method 
		if($validator->fails())
		{
			// Return to redirect to home page 
			return Redirect::to('/')->withErrors($validator);
		}
		
	}
	
	// Check is request is orderby and page only throug get 
	if($request->has('orderby') 
	   && $request->has('page') 
	   && $request->isMethod('get')
	  && count($request->all() == 2
	  )) {
		 
		// Get the order by
		$sortproduct = $request->get('orderby');
		 
		// Get the page 
		$page = $request->get('page');
		
		// Skip from 
		$skipfrom = $page * $perpage;
		
		// Check orderby value 		 
		if($sortproduct == 'price-hight-to-low')
		{
			
			// Get the cached name 
			$cachename  = 'product-order-by-price-desc';

			// Get the cached data 
			$collect = $this->ReturnDataAsArray($cachename);

			// Extract the data 
			$nextpage = $this->ExtractData($page, $perpage, $collect);
			
			// Create custom pagination 
			$pagination = $this->CustomPagination($cachename);

			// Append URL request and their value 
			$pagination->appends(['orderby'=>$request->get('orderby'),'page'=>$pagination->currentPage()])->links();

			// Return view with data 
			return View('Ketty.index',['users'=>$nextpage,'result'=>$pagination ]);
			
			// If order by value is different 
		} else if($sortproduct == 'price-low-to-high') {

			// Set the variable name 
			$cachename  = 'product-order-by-price-asc';

			// Get the array data
			$collect = $this->ReturnDataAsArray($cachename);

			// Extract the data from array 
			$nextpage = $this->ExtractData($page, $perpage, $collect);
			
			// Create custom pagination 
			$pagination = $this->CustomPagination($cachename);
			
			// Append url in pagination 
			$pagination->appends(['orderby'=>$request->get('orderby'),'page'=>$pagination->currentPage()])->links();

			// Return view with data 
			return View('Ketty.index',['users'=>$nextpage,'result'=>$pagination ]);
		}
	} else {
	
			// Get the cached name 
			$cachename = 'all-product-cached';		 

			// Return the cached data with the view 
			return $this->GetAllCachedData($request,$cachename);

			}
	
	}
	
	/* Extract the data */
	public function ExtractData($page, $perpage, $collect)
	{
		// Reduce the page by on 
		$page = $page - 1;

		// Data skip from 
		$skipfrom = $page * $perpage;

		// Get the sliced data from cached data 
		$perpage = array_slice($collect, $skipfrom );

		// Show the remaining data to view 
		$nextpage = array_slice($perpage, 0, 5 );
		
		// Return the data 
		return $nextpage;
	}
	
	/* Setting pagination */
	public function ReturnPagination($collect)
	{
		// Get the instance to create pagination 
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		
		// Create new collection with data in array 
		$getcol = new Collection($collect);
		
		// Data for perpage pagination  		
		$perpage = 5;
		
		// Perpage data from Cached data 
		$perPage = 5;
		
		// Slice the collection to get the items to display in current page
        $currentPageSearchResults = $getcol->slice($currentPage * $perPage, $perPage)->all();
		
		// Number of pagination to show in view  
		$paginatedSearchResults= new LengthAwarePaginator($currentPageSearchResults, count($getcol), $perPage);

		// Change the view of pagination url 
		 $paginatedSearchResults->withPath('http://localhost/laravelnew/public/Ketty');		
		
		// Return pagination 
		return $paginatedSearchResults;
	}

	/* Set custom pagination */
	public function CustomPagination($cachename)
	{
		// Get the collection with cached name set 
		$collect = $this->ReturnDataAsArray($cachename);
		
		// Get the instance to create pagination 
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		
		// Create new collection with data in array 
		$paginatedSearchResults = $this->ReturnPagination($collect);		
				
		// Return pagination 
		return $paginatedSearchResults;
	}	
	
	/* Get all cached data */
	public function GetAllCachedData(Request $request, $cachename)
	{
		// Get pagination result by cached name 
		$paginatedSearchResults = $this->CustomPagination($cachename);
		
		// Data per page 
		$perpage = 5;
		
		// Get the collection 
		$collect = $this->ReturnDataAsArray($cachename);
		
		// Check the page request only 
		if($request->has('page') 
		   && count($request->all() == 1 
		   && $request->isMethod('get'))) 
		{
			// get the page 
			$page = $request->get('page');
			
			// Reduce the page by on 
			$page = $page - 1;
			
			// Data skip from 
			$skipfrom = $page * $perpage;
			
			// Get the sliced data from cached data 
			$perpage = array_slice($collect, $skipfrom );
			
			// Show the remaining data to view 
			$nextpage = array_slice($perpage, 0, 5 );		
			
			// Make a view with data 
			return view('Ketty.index')->with(['users'=>$nextpage,'result'=>$paginatedSearchResults]);
		
		} else {
		
			// Slice the array and how first $perpage 
			$perpage = array_slice($collect, 0, $perpage); 
		
			// Make view with all data pagination 			
			return view('Ketty.index')->with(['users'=>$perpage, 'result'=>$paginatedSearchResults]);
		
		}	
		
		// Return false 
		return false;
	}
	
	/* Show product by id */
	public function show($id)
	{
		// Get the cached key
		$CachKey = 'all-product-cached';
		
		// Get the cached data 
		$CachedAllProduct = Cache::get($CachKey);
		
		// Get the cached data by id 
		$index = $this->ReturnIndexICD($CachedAllProduct, $id);
		
		// If id did not found 
		if($index == false)
		{
			// Then return to the home page with message 
			return Redirect::to('Ketty')->withErrors("Product was unable to found.");
		}
		
		// Reduce the index by 1
		$index = $index - 1;		
		
		// Return the data with it's value 
		return view('Ketty.show')->with(['users'=>$CachedAllProduct[$index]]);
	}		
	
	/* Return cached product by id */
	public function ReturnIndexICD($CachedAllProduct,$id)
	{
		// Set the index 
		$i = 1;
		
		// Set vairable to false 
		$pfound = false;
		
		// Loop each data 
		foreach($CachedAllProduct as $key => $value)
		{
			// Get the id 
			$cid = $value->id;
			
			// Chek if id matched 
			if($cid == $id)
			{
				// Set variable  to true 
				$pfound = true;
				
				// Break the loop
				break;
			}
			
			// Increase the index 
			$i++;
		}
		
		// Check  variable 
		if($pfound == true)
		{
			// Return the index 
			return $i;
		} 		
		
	}
	
	/* Create view */
	public function create()
	{
		// Return create view 
		return view('Ketty.create');
	}
	
	/* File upload method */
	public function store(UploadRequest $request)
	{
		// Set request data validation in array 
		$rules = array(
			'name'=>'required',
			'email' => 'required|email|unique:users,email',
			'password' => 'required',
			'confirm_password'=>'required|same:password',
			'phone' => 'required|integer',
			'remember_token' => 'N/A',
			'price' => 'required|integer|max:50000'
		);
	
		// Valid the data 
		$validator = Validator::make(Input::all(),$rules);
				
		// Validate with fails method 
		if($validator->fails())
		{
			// Return redirection 
	return Redirect::to('Ketty/create')->withErrors($validator)->withInput(Input::except('password'));;
		} else {					
			
			// Request the data 
			
			$name = Input::get('name');			
			$email = Input::get('email');				
			$password = Input::get('password');
			$phone = Input::get('phone');
			$price = Input::get('price');			
			$created_at = date('Y-m-d H:i:s');
			$updated_at = $created_at;			
			
			// Hash the password 
			$password = password_hash($password, PASSWORD_BCRYPT);			
			
			// Get the file name 
			$dbfilename = array();
			
			// Loop each file 
			foreach ($request->photos as $photo) {
            	
				// Get the file name original 
				$filename = $photo->getClientOriginalName();
				
				// Set the random number  
				$number = rand(1,100000);
				
				// Explode filename with . sign 
				$explodfname = explode('.', $filename);
				
				// Set the new filename 
				$newfilename = round(microtime(true)) .$number.'.' . end($explodfname);
        						
				// Move the file to directory 
				$photo->move('product-images/',$newfilename);
				
				// Append dbfilename to variable 
				$dbfilename[] = $newfilename;
			}
			
			// encode the in json file 
			$jsonfilesname = json_encode($dbfilename);
			
			
			// Inser the datas 
			$insert = DB::table('users')->insert(['name' => $name,'email'=>$email,
												 'password' => $password,'created_at'=>$created_at,'updated_at'=>$updated_at,'remember_token' => 'N/A','phone' =>$phone,'filesname' => $jsonfilesname, 'price' => $price]);
			
			// Flash message with session Facades 			
			Session::flash("message", "Successfully created $name");
			
			// Set all data in cached 
			$this->CacheAllData();			
			
			// Redirect to create 
			return Redirect::to('Ketty/create');
		}
		
		
	}
	
	/* Cache all database data */
	public function CacheAllData()
	{
		// Get the data 
		$all_product = DB::table('users')->get();

		// FetchProductBy DESC
		$fpbpd = DB::table('users')->orderBy('price','desc')->get();

		// Fetch product by price ASC
		$fpbpa = DB::table('users')->orderBy('price','asc')->get();

		// Cache keyS
		$CachKey = 'all-product-cached';
		
		// Product by DESC
		$CKPHTL = 'product-order-by-price-desc';
		
		// Product By ASC
		$CKPLTH = 'product-order-by-price-asc';

		// Set all product to cached 
		$this->SetCacheData($CachKey, $all_product);
		
		// Set cache data by DESC
		$this->SetCacheData($CKPHTL, $fpbpd);
		
		// Set cache data by DESC
		$this->SetCacheData($CKPLTH, $fpbpa);	
	}
	
	/* Get product in array not as  object */
	public function getAllProductInArray()
	{
		// Get the product 
		$all_product = DB::table('users')->get();
		
		// Return the all data 
		return $all_product;
	}
	
	/* Order product by DESC */
	public function OrderProductByDESC()
	{
		
	}
	
	/* Set cached data */
	public function SetCacheData($key,$data)
	{
		// Check key if exists 
		if(Cache::has($key))
			{
				// Then remove the data 
				Cache::forget($key);
				
				// Set new value 
				Cache::forever($key, $data);
			
			} else {	
				
				// Or set the value to key 
				Cache::forever($key, $data);
			}
	}
	
	/* Set cached data as Memecached */
	public function SetCacheDataAsMemeCache($key, $data)
	{
		// Check key if exists 
		if(Cache::store('memcached')->has($key))
			{
				// Then remove the data 
				Cache::store('memcached')->forget($key);
				
				// Set new value 
				Cache::store('memcached')->forever($key, $data);
			
			} else {	
				
				// Or set the value to key 
				Cache::store('memcached')->forever($key, $data);
			}
		
	}
	
	/* Return cached data as array */
	public function ReturnDataAsArray($cachedName)
	{
		// Get the data 
		$all_product = Cache::get($cachedName);
		
		// Get the collection from cached key 
		$collection = new Collection($all_product);
		
		// Remember property Items is defined as protected 
		// Illuminate\Support\Collection and edit as public 
		
		// Get the items in collection 
		$collect = $collection->items;
		
		// Return the collection
		return $collect;
	}
	
	/* Destroy the record by id */
	public function destroy($id)
	{
		// Get the filenames by id 
		$users = DB::table('users')->select('filesname')->where('id', '=' , $id)->get();
				
		// Loop each files name 		
		foreach($users as $get)
		{
			// Set the each file name 
			$filename = $get->filesname;
		}
		
		// Encode the file to array
		$decodeJson = json_decode($filename, true);
		
		// Public directory 
		$directory = public_path().'/product-images/';
		
		// File links 
		$Flink = '';
		
		// Loop each images 
		if($decodeJson != false)
		{
			// Loop each images 
			foreach($decodeJson as $key => $value)
			{
				// Set full link of the file 
				$Flink = $directory.$value;
				
				// Remove the files 
				if(!File::delete($Flink))
				{
					// Session flash the message 
					Session::flash("message", "Following file was unable to delete $Flink");
        			
					// Redirect to Home
					return Redirect::to('Ketty');
				}
			}
		}
		
		
		// Delete the data by id 
		DB::table('users')->where('id', '=', $id)->delete();
		
		// Re-cache all data 		
		$this->CacheAllData();
		
		// Flash the message 
		Session::flash("message", "Successfully deleted.");
		
		// Redirect to home 
        return Redirect::to('Ketty');
	}
	
	/* Update the record by id */
	public function edit($id)
	{
		// Find the record by id
		$find = DB::table('users')->where('id', '=', $id)->find($id);
		
		// Return with except password 
		return view('Ketty.edit')->with(['users'=>$find])->withInput(Input::except('password'));
	}
	
	/* Update file */
	public function update(UploadRequest $request, $id)
	{
		// Set data in array for validation 
		$rules = array(
			'name'=>'required',
			'email' => 'required|email',
			'password' => 'required',
			'confirm_password'=>'required|same:password',
			'phone' => 'required|numeric'
		);
		
		// Set validation 		 
		$validator = Validator::make(Input::all(),$rules);
		
		// Validate with fails method 
		if($validator->fails())
		{
			// Return redirection 
	return Redirect::to('Ketty/create')->withErrors($validator)->withInput(Input::except('password'));;
		} else {
			
		// Get the files name by id 		
		$users = DB::table('users')->select('filesname')->where('id', '=' , $id)->get();	
			
		// Loop each data 
		foreach($users as $get)
		{
			// Get the file name 
			$filename = $get->filesname;
		}
		
		// Encode the file to array
		$decodeJson = json_decode($filename, true);
		
		// Public directory 
		$directory = public_path().'/product-images/';
		
		// File link
		$Flink = '';
		
		// Loop each images 
		if($decodeJson != false)
		{
			// Loop each images 
			foreach($decodeJson as $key => $value)
			{
				$Flink = $directory.$value;
				
				// Remove the files 
				if(!File::delete($Flink))
				{
					// Session flash message 
					Session::flash("message", "Following file was unable to delete $Flink");
        			
					// Return redirection 
					return Redirect::to('Ketty');
				}
			}
		}
			
			// Get the all datas 
			$name = Input::get('name');
			$email = Input::get('email');			
			$password = Input::get('password');
			$phone = Input::get('phone');
			$created_at = date('Y-m-d H:i:s');
			$updated_at = $created_at;	
			
			// Has the password with PASSWORD BCRYPT
			$password = password_hash($password, PASSWORD_BCRYPT);
			
			// Set the file name in array 
			$dbfilename = array();
			
			// Loop each data 
			foreach ($request->photos as $photo) {
            	
				// Get original data 
				$filename = $photo->getClientOriginalName();
				
				// New file name 
				$number = rand(1,100000);
				
				// Explod filename with . sign 
				$explodfname = explode('.', $filename);
				
				// Set new filename 
				$newfilename = round(microtime(true)) .$number.'.' . end($explodfname);
        						
				// Move the file to 
				$photo->move('product-images/',$newfilename);
			
				// Append dbfilename to variable 
				$dbfilename[] = $newfilename;
			}
			
			// encode the in json file 
			$jsonfilesname = json_encode($dbfilename);			
			
			// Update the data with id 
			$update = DB::table('users')->where('id', $id)->update(['name'=> $name, 'email'=> $email, 'password' => $password, 'created_at' => $created_at, 'updated_at' => $updated_at,'remember_token' => 'N/A','phone'=>$phone,'filesname'=>$jsonfilesname]);
			
			// Re-cache everything 
			$this->CacheAllData();
			
			// Flesh the messsage 
			Session::flash('message', 'Sucessfully updated !.');
			
			// Send redirection
			return Redirect::to("Ketty");
			
		}
		
	}
	
	/* Error */
	public function error()
	{
		// Error page 
		return view('Ketty.error');
	}
	
	/* Send login view */
	public function login()
	{
		// Return login view 
		return view('Ketty.login');
	}
	
	/* Send registration view */
	public function register()
	{
		// Return register view 
		return view('Ketty.register');
	}	
	
	/* Password reset view */
	public function reset()
	{
		// Password reset view 
		return view('Ketty.passwords.reset');
	}
	
	/* Request reset password */
	public function requestResetPass()
	{
		// Request new password view 
		return view('Ketty.passwords.email');
	}	
	
	/* Admin test */
	public function AdminTest()
	{
		// Get all product 
		$product = Product::all();
		
		// Return to administrator
		return View('Ketty.administrator')
->with('products', $product);
	}
	
	/* Uploade submited files */
	public function uploadSubmit(UploadRequest $request)
    {
        // Coming soon...
    }
	
	/* Making shopping cart */ 
	public function makecart(Request $request)
	{		
		// Check request has 
		if($request->has('update_cart'))
		{
			// Get the id to upate 
			$pid = Input::get("item-id");
			
			// Get the qty 
			$qty = Input::get("quantity");
			
			// Set validation data in array 
			$rules = array(
				'quantity' => 'required|numeric|max:100'
			);
			
			// Validate qty 
			$validator = Validator::make(Input::all(),$rules);
			
			// Validate the with fails method 
			if($validator->fails())
			{	
				// Redirect with the error 
				return Redirect::to('Ketty/showcart')->withErrors($validator);
			} 
			
			// Set the index 
			$i = 0;
			
			// Lets update the session data 
			foreach(session($this->CartSession()) as $eachItem)
			{
			
				// Loop each session data 			
				while(list($key,$value) = each($eachItem))
					{
						if($key == "pid" && $value == $pid )
						{
							// Set new quentity 
							$newamount = $eachItem['price'] * $qty;
							
							// Set the new data in session with index 
							Session::put("cart_session.$i.qty", $qty);
							
							// Set the data in session with new amount 
							Session::put("cart_session.$i.totalamount", $newamount);

							// Brek the loop 
							break;

						}
					}
					
					// Increase index 
					$i++;
			}			
						
			// Send the redirection 
			return Redirect::to("Ketty/showcart")->withMessage("Cart is updated.");
			
			// If request is delete-cart 
		
		} else if($request->has('delete-cart')) {
			
			// Get the item id 
			$pid = Input::get("item-id");
			
			// Get product index in session 
			$pindex = Input::get('pindex');
			
			// Change string number to index 
			$itype = settype($pindex, 'integer');
			
			// Get the session data 
			$getdata = session('cart_session');
			
			// Get produc name 
			$pname = $getdata[$pindex]['pname'];
			
			// Unset the session 
			unset($getdata[$pindex]);
			
			// Sort the data 
			sort($getdata);
			
			// Set new value to session 
			session(['cart_session' => $getdata]);
			
			// Send redirection 
			return Redirect::to("Ketty/showcart")->withMessage("Product $pname is removed from cart.");
		}
		// Check 
			
		/*
		// Destroy the session is set 
		if($request->session()->has($this->CartSession()))
		{
			$request->session()->flush();
			
			return ;
		}
		*/
		
		$id = Input::get('id');
		$qty = Input::get('qty');
		$images = Input::get('images');
		$price = Input::get('price');
		$name = Input::get('name');
		
		/*
		// Getting all post data
		if(Request::ajax()) {
		  $data = Input::all();
		  print_r($data);
		}
		*/
		$totalamount = $qty * $price;
		
		// Form array containing data 
		$sessionData = ['pid' => $id, 'qty' => $qty, 'images' => $images, 'price' => $price, 'pname' => $name, "totalamount" => $totalamount];
		
		if(!Session::has($this->CartSession()))
		{
			session([$this->CartSession() => array(0 => $sessionData)]);
			
			return Redirect::to("Ketty/$id")->withMessage("Product $name was sucessfully added to cart !.");
		} 
		
		// Set the same product 
		$sameProduct = false;
		
		// Set the index 
		$i = 0;
		
		
		$cart  = session('cart_session');
		
		$cartsession = Session::get('cart_session');
		
		foreach(session($this->CartSession()) as $eachItem)
		{
			// Increase the index 
			
			
			while(list($key,$value) = each($eachItem))
			{
				if($key == "pid" && $value == $id)
				{					
					$newitem = $eachItem['qty'] + $qty;
					$newamount = $eachItem['totalamount'] + $totalamount;
					
					Session::put("cart_session.$i.qty", $newitem);
					Session::put("cart_session.$i.totalamount", $newamount);
					
					$sameProduct = true;
					
					break;
					
					
				}
			}
			
			$i++;
		}
		
		
		
		if($sameProduct == false)
		{
			$request->session()->push('cart_session', $sessionData);
				
			//$type = gettype(session($cart));
			return Redirect::to("Ketty/$id")->withMessage("Product is added.");
		} else {
		
			
			return Redirect::to("Ketty/$id")->withMessage("Product is added.");
		}
		
		
			
		
		
		
	}
	
	/* Setting cart session */
	public function CartSession()
	{
		// Return session data 
		return 'cart_session';
	}
	
	/* Show cart */
	public function showcart(Request $request)
	{
		// Count the session data in array 
		$countsession = count(session('cart_session'));
		
		// If there is no session set 
		if($countsession < 1 || $request->session()->has('cart_session') != true)
		{
			// Count the data 
			$data = count(session('cart_session'));
			
			// Redirect to the page 
			return Redirect::to("Ketty")->withMessage("Your cart is empty !.");
		}			
		
		// Get the session data 
		$sessionData = session('cart_session');
		
		// Total quentity 
		$totalqty = '';
		
		// Total amount 
		$totalamount = '';
		
		// Check the session 
		if($request->session()->has('cart_session'))
		{
			// Loop each session items 
			foreach(session($this->CartSession()) as $eachItem)
			{
				// Append each qty index 
				$totalqty += $eachItem['qty'];
				
				// Append each total amount 
				$totalamount += $eachItem['totalamount'];
				
			}
			
			// Get the paypal button as well 
			$payment = $this->PayPalAPI($request);
			
			// Set the variable value and index in array 
			$total = array('totalqty' => $totalqty, 'totalamount' => $totalamount,'CC' => '$', 'payment' => $payment);
					
		}		
		
		// Return view 
		return View('Ketty.showcart',['usercart'=>$sessionData,'total' => $total]);
	}
	
	/* Get total cart values */
	public function carttotal()
	{
		// Set total qty 
		$totalqty = '';
		
		// Set the total amount 
		$totalamount = '';
		
		// Check if request is exist 
		if($request->session()->has('cart_session'))
		{
			// Loop each request 
			foreach(session($this->CartSession()) as $eachItem)
			{
				// Append each quentity 
				$totalqty += $eachItem['qty'];
				
				// Append total amount 
				$totalamount += $eachItem['totalamount'];
				
			}
			
			// Append items in array 
			$total = array('totalqty' => $totalqty, 'totalamount' => $totalamount);
			
			// Return the variable 
			return $total;
		}
	}	
	
	/* Return empty cart */
	public function empty_cart()
	{
		// Return empty cart view
		return view('Ketty.empty_cart');
	}
	
	/* Set paypal api*/
	public function PayPalAPI(Request $request)
	{
		// Count the session data 
		$countsession = count(session('cart_session'));
		
		// If session data count is more then 0
		if($countsession > 0 || $request->session()->has('cart_session') == true)
		{
		
		// Set the paypal checkout button 
		$pp_checkout_btn = "";		
		
		// Get variable if user is 
		$ifuserlogin = Auth::check();
		
		// Check if user is logged in 
		if($ifuserlogin !== true)
		{
			// Check button for login 
			$pp_checkout_btn = '<a href = "login" class="btn btn-success"><i class = "fa fa-sign-in"></i> Login</a>';
			
			// Return the button 
			return $pp_checkout_btn;
		}
		
		/* This is paypal form */
		$pp_checkout_btn .= '<input type = "hidden" name = "cmd" value ="_cart" />
							<input type = "hidden" name = "upload" value = "1" />
							<input type = "hidden" name = "business" value = "'.$this->PaypalEmail.'" />';
		
		// Set the index 
		$i = 0;
		
		// Each itmes details 
		foreach(session('cart_session') as $eachItem)
		{
			// Increase index by 1
			$x = $i + 1;
			
			// Get product name 
			$productName = $eachItem["pname"];
			
			// Get the product price 
			$productPrice = $eachItem["price"];
			
			// Get the product qty 
			$Quentity = $eachItem["qty"];
			
			// Get the product id 
			$pid = $eachItem["pid"];
			
			// Set each items in paypal form 
			$pp_checkout_btn .= '
			<input type="hidden" name="item_number_'.$x.'" value="'.$pid.'" />
			<input type="hidden" name="item_name_'.$x.'" value="'.$productName.'" />								
					<input type="hidden" name="amount_'.$x.'" value="'.$productPrice.'" />
					<input type="hidden" name="quantity_'.$x.'" value="'.$Quentity.'" />';
		
		
			// Increase index by 
			$i++;
		}
		
		// Set the paypal variable  
		$pp_checkout_btn .= '<input type="hidden" name="notify_url" value= "'.$this->p_notify_url.'" />
							<input type="hidden" name="return" value= "'.$this->p_return.'" />
							<input type="hidden" name="rm" value="2" />
							<input type="hidden" name="cbt" value= "'.$this->p_cbt.'" />
							<input type="hidden" name="cancel_return" value= "'.$this->p_cancel_return.'" />
							<input type="hidden" name="Ic" value= "'.$this->p_Ic.'" />
							<input type="hidden" name="currency_code" value="USD" />
								<!--
								<input type="image"  value="XXX" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make Payments with Paypal" />
								<button type="button" title="Continue Shopping" class="button btn-continue"><span><span>Continue Shopping</span></span></button>
								-->
								<button  value="XXX"  name="submit" alt="Make Payments with Paypal" class = "button btn-continue" /><span><span><i class = "fa fa-paypal"></i> Paypal</span></span></button>
			';
	
		// Return the button 
		return $pp_checkout_btn;		
			
		}
		
	}	
	
	/* Set paypal checkout complet */
	public function paypalcheckoutcomplete(Request $request)
	{
		// Check if method is post 
		if($request->isMethod('post'))
		{
			// Check some index send by paypal and its value 
			if(Input::get('payment_status') == 'Completed')
			{			
				// Get all data send by paypal 
				$data = Input::all();				
		
				// Get the txn id 
				$txn_id = $data['txn_id'];		
					
				// Find the txn id 
				$query = DB::table('purchaseorder')->where('data->txn_id', $txn_id)->get();
		
				// Data must not contain txn_id  				
				if(count($query) !== 0)
				{
					// Send error with redirection 
					return Redirect::to('Ketty/payment/paypal_cancel')->withErrors("Payment txn id injection, Could not complete payment.");
				}				
		
				// Encode to json 
				$data = json_encode($data);
		
				// Insert the data 
				DB::table("purchaseorder")->insert(['data' => $data]);
		
				// Unset the session 
				$request->session()->forget('cart_session');
				
				// Make a view 
				return view('Ketty.payment.papal-checkout-complete')->with(['msg' =>'Your payment confirmed !']);
				 
			}
		}
				
			
				
		
	}		
	
	/* Set administrator */
	public function administrator()
	{
		// Return administrator index 
		return view ('Ketty/administrator/index');
	}
	
	/* Searc data in array as string */
	public function searchString(Request $request)
	{
		// Set the string in array 
		$foundsomthing = array();
		
		// Check if request is search 
		if($request->get('search') == '')
		{
			// Send the redirection 
			return Redirect::to('Ketty');
		
		}
		
		// Check the string 
		if($request->has('search') 
		   && $request->has('_token')
		  && count($request->all()) == 2)
		{			
			
			// Get the request 
			$STS = $request->get('search');					
			
			// Get the all cached product 
			$CachKey = 'all-product-cached';
			
			// Get the cached all data 
			$gcd = Cache::get($CachKey);
			
			// Search in the array 
			foreach($gcd as $key => $value)
			{
				// Access each name 
				$names = $value->name;
				
				// Check with string 
				if(strpos(trim(strtolower($names)), trim(strtolower($STS))) !== false)
				{
					// Append each  array
					$foundsomthing[$key] = $value;
				}
			}
			
			// Sort the string 
			sort($foundsomthing);
			
			// Set the page 
			$page = 0;
			
			// Get perpage 
			$perpage = $this->perPage();
			
			// Slice the result in array 
			$result = array_slice($foundsomthing, 0, $perpage);
		
			// If request is page, _token, search
			} else if ($request->has('page') 
					&& $request->has('_token')
					&& $request->has('search'))
			{
				
			// Get the request 
			$STS = $request->get('search');
			
			// Get the all product cached 
			$CachKey = 'all-product-cached';
			
			// Get the cached all data 
			$gcd = Cache::get($CachKey);
			
			// Search in the array 
			foreach($gcd as $key => $value)
			{
				// Access each name 
				$names = $value->name;
				
				// Check with string 
				if(strpos(trim(strtolower($names)), trim(strtolower($STS))) !== false)
				{
					// Append each  array
					$foundsomthing[$key] = $value;
				}
			}
			
			// Sort the array 
			sort($foundsomthing);
			
			// Get the product per page 
			$perpage = $this->perPage();
			
			// Set the page 
			$page = $request->get('page');
			
			// Extract the data 
			$result = $this->ExtractData($page, $perpage, $foundsomthing);			
			
		}
		
		// Check array 
		if(count($foundsomthing) == 0)
		{
			// Send redirection with error msg 
			return Redirect::to('Ketty')->withErrors("Could not be able to found for $STS.");
		}		 
		
		// Get the custom pagination 
		$paginatedSearchResults = $this->ReturnPagination($foundsomthing);	

		//$pagination = $this->CustomPagination($foundsomthing);
		$paginatedSearchResults->appends(['_token'=> $request->get('_token'),'search'=>$request->get('search'),'page'=>$paginatedSearchResults->currentPage()-1])->links();
		
		// Set pagination path 
		$paginatedSearchResults->withPath('http://localhost/laravelnew/public/Ketty/search');

		// Send view with message 
		return view('Ketty.search')->with(['users'=>$result, 'result' => $paginatedSearchResults]);

	}
	
	/* Search data */
	public function search()
	{
		// Send the search view 
		return view('Ketty.search');
	}
	
	/* Return perpage */
	public function perPage()
	{
		// Return 5 integer 
		return 5;
	}
	
	/* Email template */
	public function EmailTemplate()
	{
		// Return email template 
		return view('Ketty.email-template');
	}
	
}
