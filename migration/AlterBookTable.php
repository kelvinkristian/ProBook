<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 29/11/18
 * Time: 16:35
 */

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("alter table book drop column `name`,drop column `author`, drop column `description`, drop column imgsrc;");
$stmt->execute();

$stmt = $conn->prepare("alter table book add column book_id varchar(30)");
$stmt->execute();