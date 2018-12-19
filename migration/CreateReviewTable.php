<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 24/10/18
 * Time: 22:40
 */

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("Create table review (id int auto_increment, comment text not null, star int not null, primary key(id))");

$stmt->execute();