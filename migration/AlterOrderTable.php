<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 24/10/18
 * Time: 21:25
 */

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("alter table `order` add column `created_at` timestamp default current_timestamp;");

$stmt->execute();