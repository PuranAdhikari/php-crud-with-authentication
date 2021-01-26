<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "db_ismt";

	//create a connection (procedural)
	$conn = new mysqli($servername, $username, $password, $database);

	//check for the connection_aborted()
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

?>