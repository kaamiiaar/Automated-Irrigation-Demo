<?php

class DB_CONNECT {
    private $con;
 
    // Constructor
    function __construct() {
        // Trying to connect to the database
        $this->connect();
    }
 
    // Destructor
    function __destruct() {
        // Closing the connection to database
        $this->close();
    }
 
   // Function to connect to the database
    function connect() {

        //importing dbconfig.php file which contains database credentials 
        $filepath = realpath (dirname(__FILE__));

        require_once($filepath."/dbconfig.php");
        
		// Connecting to mysql (phpmyadmin) database
        $this->con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
 
        // Checking the connection
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
 
        // returing connection cursor
        return $this->con;
    }
 
	// Function to close the database
    function close() {
        // Closing data base connection
        $this->con->close();
    }
 
}
 
?>