<?php

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("CREATE TABLE book (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30) NOT NULL, author VARCHAR(30) NOT NULL, description TEXT, vote INT, rating FLOAT, imgsrc VARCHAR(30))");

$stmt->execute();