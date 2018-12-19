<?php
/**
 * Created by PhpStorm.
 * User: Gabriel B.R
 * Email: gabriel.bentara@gmail.com
 * Date: 22/10/18
 * Time: 6:02 PM
 */

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("create table user (id int AUTO_INCREMENT, name varchar(50) not null, "
    . "username varchar(30) not null, email varchar(50) not null, password varchar(30) not null,"
    . "address text not null, phone varchar(30) not null, avatar varchar(30) not null, "
    . "primary key (id))");

$stmt->execute();