<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitness";
$conn = new mysqli($servername, $username, $password, $dbname);