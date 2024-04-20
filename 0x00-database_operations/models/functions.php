<?php
/**
 * connect_to_database - Connect to database
 * @param string $server_name: The name of the server
 * @param string $user_name: The user of the database
 * @param string $password: Password of the database
 * 
 * return: mysqli|false An object of mysqli on success, otherwise false
 */
function connect_to_database($server_name, $user_name, $password)
{
	$con = new mysqli($server_name, $user_name, $password); # create an obj of mysql

	/* Check if connection was successful */
	if ($con->connect_error)
	{
		die("Connection to database failed: {$con->connect_error}");
		return false;
	}
	return $con;
}

/**
 * create_database: Create Database if not exists
 * @param string $database_name: The name of the database
 * @param object $con: An object of mysqli
 * 
 * return: None
 */
function create_database($database_name, $con)
{
	/* Read the SQL query from the file */
	$sql = "CREATE DATABASE IF NOT EXISTS $database_name";

	/* Execute the query and handle error */
	if ($con->query($sql))
		return true;
	else
		exit("Database creation failed: {$con->connect_error}");
}

/**
 * create_table: Create table if not exists
 * @param string $database_name: The name of the database
 * @param string $table_name: The name of the table
 * @param string $table_attr: The table attributes
 * @param object $con: An object of mysqli
 * 
 * return: void
 */
function create_table($database_name, $table_name, $table_attr, $con)
{
	/* Generate the SQL query dynamically */
	$sql = "CREATE TABLE IF NOT EXISTS $table_name ($table_attr)";

	/* Execute the query and handle error */
	if ($con->query($sql))
		return true;
	else
		exit("Table creation failed: {$con->error}");
}


/**
 * insert_data: Insert data into a table using prepared statements
 * @param string $table: The name of the table
 * @param array $attributes: An array of table attributes (columns)
 * @param array $values: An array of values corresponding to the attributes
 * @param object $con: An object of mysqli
 * @return void
 */
function insert_data($table, $attributes, $values, $con)
{
	/* Prepare SQL statement with placeholders */
	$sql = "INSERT INTO $table (";
	$sql .= implode(", ", $attributes) . ") VALUES (";
	$sql .= rtrim(str_repeat("?, ", count($values)), ", ") . ")";

	/*  Initialize a prepared statement */
	$stmt = $con->prepare($sql);

	/* Dynamically determine the data types and bind parameters */
	$bind_params = [];
	foreach ($values as $value)
	{
		if (is_int($value))
			$bind_params[] = "i"; // Integer
		elseif (is_float($value))
			$bind_params[] = "d"; // Double
		elseif (is_string($value))
			$bind_params[] = "s"; // String
		else
			$bind_params[] = "b"; // Blob
	}

	/* Bind parameters to the prepared statement */
	$stmt->bind_param(implode("", $bind_params), ...$values);

	/* Execute the statement and handle error */
	if ($stmt->execute()) {
		return true;
	} else {
		echo "Error inserting data: {$stmt->error}";
	}
}
?>
