<?php

$file = '../../vendor/autoload.php';
if (file_exists($file)) {
    include_once $file;
}

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../')->load();

$dbHost = 'minical-db-1'/*getenv("DATABASE_HOST")*/;
$dbUser = 'root'/*getenv("DATABASE_USER")*/;
$dbPass = 'root'/*getenv("DATABASE_PASS");*/;
$dbName = 'minical_db'/*getenv("DATABASE_NAME")*/;
$projectUrl = 'http://localhost:8080/public'/*getenv("PROJECT_URL")*/;
$apiUrl = 'http://localhost:8080/api'/*getenv("API_URL")*/;
$environment = 'development'/*getenv("ENVIRONMENT")*/;

$mysqli_connection = @mysqli_connect("$dbHost", "$dbUser", "$dbPass", "$dbName");
if (!$mysqli_connection) {
    echo json_encode(array('success' => false, 'message' => "Database connection failed with error: " . mysqli_connect_error()), true);
    return;
}else{
    
    $query = "SELECT count(*) AS TOTALNUMBEROFTABLES  FROM INFORMATION_SCHEMA.TABLES  WHERE TABLE_SCHEMA = '$dbName'";
     if($result = mysqli_query($mysqli_connection, $query)){
            $row = mysqli_fetch_row($result);
            echo json_encode(array('success' => true, 'message' => $row[0]), true);
            return;
        }
    echo json_encode(array('success' => false, 'error' => 'Database validation failed'), true);  
    return;
}


