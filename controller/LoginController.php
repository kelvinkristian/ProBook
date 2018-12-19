<?php
/**
 * Created by PhpStorm.
 * User: Gabriel B.R
 * Email: gabriel.bentara@gmail.com
 * Date: 23/10/18
 * Time: 7:49 AM
 */

require "controller/BaseController.php";
require "core/View.php";
require_once "model/UserModel.php";
require_once "core/Session.php";

class LoginController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function view()
    {
        View::render("Login", []);
    }

    public function login()
    {
        $username = $this->request->post("username");
        $password = $this->request->post("password");

        if ($username == "" || $password == "") {
            View::render("Login",[
                "error" => "Invalid username or password"
            ]);
            return;
        }

        $user = new UserModel();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->loadFromUserPass();

        if ($user->getId() == null) {
            View::render("Login",[
                "error" => "Invalid username or password"
            ]);
            return;
        }

        $session = new Session();
        $session->setSession($user->getId(), $username);
        View::render("Login", [
            "success" => "Successfully Login"
        ]);

        View::redirect("/search");

    }
}