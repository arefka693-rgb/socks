<?php
$conn = mysqli_connect("localhost", "root", "", "socksstore_db");
if($conn==TRUE)
echo "Successfully connected to the database.";   
else 
echo "Failed to connect to the database!";
?>