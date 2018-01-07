<?php

// Practice you sql here 
// Writing class for connection 
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
		return new PDO("mysql:host=localhost;dbname=wrpracti_bookinfo", $this->username, $this->password);
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

function Sql($mysqli, $sql)
{
	if(!$sql = $mysqli->query($sql))
	{
		die($mysqli->error);
	}
	
	return $sql;
}



$connection = new PDO("mysql:host=localhost;dbname=wrpracti_bookinfo", 'root', 'root');
	

// Run the prepared pdo statement 
$sql = 'SELECT COUNTRY_NAME, COUNTRY_ID, REGION_ID from countries where COUNTRY_NAME = :COUNTRY_NAME';

// PREPARE THE STATEMENT 
$sth = $connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$sth->execute(array(':COUNTRY_NAME' => 'Argentina'));

// FETCH ALL DATAS



$countries = $sth->fetchAll();
foreach($countries as $countrie)
{
	//echo  $countrie['COUNTRY_NAME'];
}


/* You can execute another query */
$sth->execute(array(':COUNTRY_NAME' => 'China'));

// Fetch all data 
$nepal = $sth->fetchAll();

$sql = 'SELECT 5,10,15';

//$sth->execute($sql);

$mysqli = new mysqli('localhost', 'root', 'root', 'wrpracti_bookinfo');


//$sql = "SELECT id FROM departments";



$sql = "CREATE TABLE if  not exists coutries(
		country_id int(15),
		country_name varchar(150),
		region_id int(15))";


//Sql($mysqli, $sql);

//$sql = "CREATE TABLE IF NOT EXISTS dum_copy_countries LIKE countries";

//Sql($mysqli, $sql);

//var_dump($countries);


// creating a duplicate copy of countries table including structure and data by name
$sql = "CREATE TABLE IF NOT EXISTS dum_countries AS SELECT * FROM countries";

Sql($mysqli, $sql);

// Create table countries set a constraint null
$sql = "CREATE TABLE IF NOT EXISTS countries
(COUNTRY_ID varchar(2) NOT NULL,
COUNTRY_NAME varchar(40) NOT NULL,
REGION_ID decimal(10,0) NOT NULL)";

Sql($mysqli, $sql);

// CREATE TABLE 
$sql = "CREATE TABLE IF NOT EXISTS test_jobs (job_id int(50) AUTO_INCREMENT PRIMARY KEY,
job_title VARCHAR(35) NOT NULL,
MIN_SALARY decimal(6,0),
MAX_SALARY decimal(6,0)
CHECK(MAX_SALARY<=25000))";

Sql($mysqli, $sql);

$sql = "CREATE TABLE IF NOT EXISTS countries(country_id int(15) AUTO_INCREMENT PRIMARY KEY,
COUNTRY_NAME varchar(40)
CHECK(COUNTRY_NAME IN('italy', 'india', 'china')),
REGIONAL_ID decimal(10,0)
)";

Sql($mysqli, $sql);

// Again 
$sql = "CREATE TABLE IF NOT EXISTS countries(COUNTRIES_ID int(15) AUTO_INCREMENT PRIMARY KEY,
COUNTRY_NAME varchar(40) NOT NULL,
CHECK(COUNTRY_NAME IN('italy','india', 'china')),
REGIONAL_ID decimal(10,0))";

Sql($mysqli, $sql);

// 
$sql = "CREATE TABLE IF NOT EXISTS job_history_test
(COUNTRY_ID INT(50) AUTO_INCREMENT PRIMARY KEY,
EMPLOYEE_ID INT(50), START_DATE date, END_DATE date, JOB_ID int(50), DEPARTMENT_ID INT(50) NOT NULL
CHECK(END_DATE LIKE('--/--/----')))";

Sql($mysqli, $sql);


// Creating jobs table 
$sql = "CREATE TABLE IF NOT EXISTS jobs_testing(
JOB_ID int(50) NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
JOB_TITLE VARCHAR(50) NOT NULL DEFAULT ' ',
MIN_SALARY decimal(6,0) DEFAULT 8000,
MAX_SALARY decimal(6,0) DEFAULT NULL
)ENGINE=InnoDB"; 


//Sql($mysqli, $sql);

// Creating employees table 
$sql = "CREATE TABLE IF NOT EXISTS employees_test( 
EMPLOYEE_ID decimal(6,0) NOT NULL PRIMARY KEY, 
FIRST_NAME varchar(20) DEFAULT NULL, 
LAST_NAME varchar(25) NOT NULL, 
JOB_ID varchar(10) NOT NULL, 
SALARY decimal(8,2) DEFAULT NULL, 
FOREIGN KEY(JOB_ID) 
REFERENCES  jobs(JOB_ID)
ON DELETE NO ACTION 
ON UPDATE NO ACTION
)ENGINE=MyISAM;";

Sql($mysqli, $sql);
/* Remember while referecing to another table alwayse column type and table must be same 
as defined in referecing table 
*/

$sql = "CREATE TABLE IF NOT EXISTS employees_test1(
		EMPLOYEER_ID decimal(6,0) NOT NULL PRIMARY KEY,
		FIRST_NAME varchar(20) DEFAULT NULL,
		LAST_NAME VARCHAR (20) NOT NULL,
		JOB_ID VARCHAR (10) NOT NULL,
		SALARY decimal(8,2) DEFAULT NULL,
		FOREIGN KEY (JOB_ID)
		REFERENCES jobs(JOB_ID)
		ON DELETE NO ACTION
		ON UPDATE NO ACTION
	) ENGINE = MyISAM";
	
//Sql($mysqli, $sql);


$sql = "SELECT first_name from employees WHERE first_name LIKE '%b%' AND first_name LIKE '%c%'";

//VAR_DUMP(Sql($mysqli, $sql));


$sql = "SELECT last_name, job_id, salary FROM employees WHERE job_id IN ('IT_PROG', 'SH_CLERK')
AND salary NOT IN (4500, 10000, 15000)";

//VAR_DUMP(Sql($mysqli, $sql));
$sql = "SELECT last_name from employees where last_name LIKE '______'";
//VAR_DUMP(Sql($mysqli, $sql));

$sql = "SELECT last_name from employees where last_name LIKE __e%";

// Select to display the jobs/designations available in the employeees table 
$sql = "SELECT DISTINCT job_id FROM employees";

//VAR_DUMP(Sql($mysqli, $sql));

$sql = "SELECT first_name, last_name, salary, salary*.15 PF from employees ";

//VAR_DUMP(Sql($mysqli, $sql));

$sql = "SELECT * FROM employees WHERE last_name IN('JONES', 'BLAKE', 'SCOTT', 'KING', 'FORD')";


$sql = "SELECT first_name, last_name, salary, department_id FROM employees  WHERE salary NOT BETWEEN 10000 AND 15000 AND 
department_id IN (30, 100)";

//VAR_DUMP(Sql($mysqli, $sql));
$sql = "SELECT first_name, last_name and department_id from employees WHERE department_id 
IN('30', '100') ORDER BY ASC";

$sql = "SELECT * FROM employees WHERE last_name IN ('BLAKE', 'SCOTT','KING', 'FORD')";

/* Update */

$sql = "UPDATE employees SET salary= 
CASE DEPARTMENT_ID 
WHEN 40 THEN salary+(salary*.25)
WHEN 90 THEN salary+(salary*.15)
WHEN 110 THEN salary+(salary*.10)
ELSE salary
END WHERE DEPARTMENT_ID IN(40,50,60,70,80,90,100)";

// Updating sql table on the basis of department id 
$sql = "UPDATE employees SET SALARY =
CASE DEPARTMENT_ID
WHEN 90 THEN SALARY+(SALARY*.10)
WHEN 110 THEN SALARY+(SALARY*.15)
WHEN 100 THEN SALARY+(SALARY*.8)
ELSE 
SALARY
END
WHERE DEPARTMENT_ID IN(40, 50, 60, 70, 80, 90, 100)";

//VAR_DUMP(Sql($mysqli, $sql));

$sql = "UPDATE jobs, employees 
SET jobs.min_salary=jobs.min_salary+2000,
jobs.max_salary=jobs.max_salary+2000,
employees.salary=employees.salary+(employees.salary*.20),
employees.commission_pct=employees.commission_pct+(employees.commission_pct*.10)
WHERE jobs.job_id='AD_PRES'
AND employees.job_id='AD_PRES'";


VAR_DUMP(Sql($mysqli, $sql));
