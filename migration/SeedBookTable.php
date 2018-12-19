<?php

require "core/config.php";

$conn  = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->prepare("insert into book(name, author, description, vote, rating, imgsrc) values ('Harry Potteru Ichi', 'J. K. Rollingu', 'First Installment of Harry Potteru', 0, 0, '/static/book_cover.jpeg')")->execute();
$conn->prepare("insert into book(name, author, description, vote, rating, imgsrc) values ('Harry Potteru Ni', 'J. K. Rollingu', 'Second Installment of Harry Potteru', 0, 0, '/static/book_cover.jpeg')")->execute();
$conn->prepare("insert into book(name, author, description, vote, rating, imgsrc) values ('Harry Potteru San', 'J. K. Rollingu', 'Third and Last installment of Harry Potteru, in this book, Harry dies.', 0, 0, '/static/book_cover.jpeg')")->execute();