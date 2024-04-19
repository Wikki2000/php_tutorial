<?php
include "models/functions.php";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Handle form submission 
	$name = $_POST["name"];
	$email = $_POST["email"];
	$comment = $_POST["comment"];
	/* Connect to the database */
	$con = connect_to_database("localhost", "root", "");

	/* Attempt to create the database */
	$database_name = "users";
	create_database($database_name, $con);

	/* Select the created database */
	$con->select_db($database_name);

	/* Define table attributes and create the table */
	$table_name = "users_info";
	$table_attr = "id INT AUTO_INCREMENT PRIMARY KEY, Name VARCHAR(50), Email VARCHAR(50), Comment TEXT";
	create_table($database_name, $table_name, $table_attr, $con);


	/* Define the table name, attributes, and values for insertion */
	$table = "users_info";
	$attributes = ["Name", "Email", "Comment"];

	$values = ["$name", "$email", "$comment"];

	/* Insert data into the table */
	insert_data($table, $attributes, $values, $con);

	/* Close the database connection */
	$con->close();
}
?>
