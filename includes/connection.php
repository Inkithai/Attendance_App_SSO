<?php
    // Creating an array for DB connection
    $db = array(
        'db_host' => "localhost",
        'db_user' => "root",
        'db_pass' => "",
        'db_name' => "attendancetask"
    );

    // Establishing the database connection
    $connection = new mysqli(
        $db['db_host'],
        $db['db_user'],
        $db['db_pass'],
        $db['db_name']
    );

    // Checking the connection status
    if ($connection->connect_error) {
        die("Connection Failed: " . $connection->connect_error);
    }

    echo "Connected successfully";
?>
