<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 13/10/18
 * Time: 12:26 PM
 */

require_once "core/StaticFile.php";
require_once "core/Router.php";

if (StaticFile::is_static()) {
    return False;
}

ini_set("soap.wsdl_cache_enabled", 0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$router = new Router();

$router->check("NotLoggedInMiddleware", "Register@view");
$router->check("NotLoggedInMiddleware", "Register@register");
$router->get("/register", "Register@view");
$router->post("/register", "Register@register");
$router->get("/check-user/:username", "Register@checkUsername");
$router->get("/check-email", "Register@checkEmail");

$router->check("NotLoggedInMiddleware", "Login@view");
$router->check("NotLoggedInMiddleware", "Login@login");
$router->get("/login", "Login@view");
$router->post("/login", "Login@login");

$router->post("/oauth", "GoogleOAuth@oauth");

$router->check("LoggedInMiddleware", "Logout@logout");
$router->get("/logout", "Logout@logout");

$router->check("LoggedInMiddleware", "Search@landing");
$router->get("/search", "Search@landing");

$router->check("LoggedInMiddleware", "Review@view");
$router->get("/review/:order", "Review@view");
$router->post("/review/:order", "Review@review");

$router->check("LoggedInMiddleware", "Book@detail");
$router->get("/book/:id", "Book@detail");

$router->check("LoggedInMiddleware", "Order@new");
$router->post("/order", "Order@new");

$router->check("LoggedInMiddleware", "Profile@view");
$router->get("/profile", "Profile@view");

$router->check("LoggedInMiddleware", "Edit@view");
$router->check("LoggedInMiddleware", "Edit@editprofile");
$router->get("/edit", "Edit@view");
$router->post("/edit", "Edit@editprofile");

$router->check("LoggedInMiddleware", "History@view");
$router->get("/history", "History@view");

$router->get("/", "Search@landing");
$router->get("/search-google/:keyword", "Search@googleBookSearch");


$router->execute();
