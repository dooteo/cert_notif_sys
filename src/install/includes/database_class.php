<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['DBhostname'],$data['DBuserName'],$data['DBpassword'],'');

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['DBname']);

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['DBhostname'],$data['DBuserName'],$data['DBpassword'],$data['DBname']);

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Open the default SQL file
		// and Create website administrator count
		$query = file_get_contents('assets/install.sql');
		$query .= "INSERT INTO `".$data['DBPrefix'] . "admin` VALUES (1, ";
		$query .="'". $data['webadmin']."',";
		$query .="'". SHA1($data['webadminpass']) ."','','','','','',1);";
		
		// Execute a multi query
		$mysqli->multi_query($query);

		// Close the connection
		$mysqli->close();

		return true;
	}
}
