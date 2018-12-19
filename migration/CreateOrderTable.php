<?php

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("CREATE TABLE `order` (id INT AUTO_INCREMENT, book_id INT NOT NULL, user_id INT NOT NULL, review_id INT, amount INT NOT NULL, created_at timestamp default current_timestamp, primary key(id));");

$stmt->execute();