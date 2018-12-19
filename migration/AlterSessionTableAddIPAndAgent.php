<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 30/11/18
 * Time: 14:51
 */

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("alter table session add column agent varchar(255), add column ip varchar(20)");

$stmt->execute();