<?php
$sql = "CREATE TABLE if not exists countires(
		country_id int(15),
		country_name varchar(150,
		region_id int(15))";
		
$sql = "CREATE TABLE IF NOT EXISTS dum_countries AS SELECT * FROM countries";

$sql = "CREATE TABLE IF NOT EXISTS countries (COUNTRY_ID varchar(2)
NOT NULL,
COUNTRY_NAME varchar(40) NOT NULL,
REGION_ID decimal(10,0) NOT NULL)";

$Sql = "CREATE TABLE IF NOT EXISTS test_jobs (job_id int(50)
AUTO_INCREMENT PRIMARY KEY,
job_title VARCHAR (35) NOT NULL,
MIN_SALARY decimal(6,0),
MAX_SALARY decimal(6,0)
CHECK(MAX_SALARY <= 25000))";

$sql = "CREATE TABLE IF NOT EXISTS countries(
countries_id int(15) AUTO_INCREMENT PRIMARY KEY,
COUNTRY_NAME VARCHAR(40)
CHECK(COUNTRY_NAME IN('italy', 'india', 'china')),
REGIONAL_ID decimal(10,0))";

$sql = "CREATE TABLE IF NOT EXISTS job_history_test(
COUNTRY_ID INT(50) AUTO_INCREMENT PRIMARY KEY,
EMPLOYEE_ID INT(50), START_DATA DATE, END_DATE date,
JOB_ID int(50), DEPARTMENT_ID INT(50) NOT NULL
CHECK(END_DATE LIKE('--/--/-----')))";

$sql = "SELECT first_name from employees WHERE first_name LIKE '%b%' 
AND first_name LIKE '%C%'";

$sql = "SELECT last_name, job_id salary FROM employees WHERE job_id IN
('IT_PROG', 'SH_CLERK') AND salary NOT IN (4500, 10000, 150000)";

$sql = "SELECT last_name from employees WHERE last_name LIKE '______'";

$sql = "SELECT last_name from employees WHERE last_name LIKE __e%";

$sql = "SELECT DISTINCT job_id FROM employees";

$sql = "SELECT * FROM employees WHERE last_name IN('JONES', 'BLAKE','SCOTT', 'KING', 'FORD')";


?>