<?php
/**
 * Created by PhpStorm.
 * User: Gabriel B.R
 * Email: gabriel.bentara@gmail.com
 * Date: 22/10/18
 * Time: 6:23 PM
 */

require "controller/BaseController.php";
require "core/View.php";
require_once "core/Session.php";
require "model/UserModel.php";

class RegisterController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function view()
    {
        View::render("Register", []);
    }

    public function checkUsername()
    {
        $user = new UserModel();
        $username = $this->request->param("username");
        $user->setUsername($username);
        if ($user->checkUserExists()) {
            echo "error";
            return;
        }

        echo "ok";
    }

    public function checkEmail()
    {
        $user = new UserModel();
        $email = $this->request->get("email");
        $user->setEmail($email);
        if ($user->checkUserExists()) {
            echo "error";
            return;
        }

        echo "ok";
    }

    // public function checkCard()
    // {
    //     $user = new UserModel();
    //     $card = $this->request->get("card");
    //     $user->setCard($card);
    //     if ($user->checkUserExists()) {
    //         echo "error";
    //         return;
    //     }

    //     echo "ok";
    // }

    public function register()
    {
        if (!$this->request->validatePostNotEmpty()) {
            View::render("Register", [
                "error" => "All fields must be filled"
            ]);
            return;
        }

        $name = $this->request->post("name");
        $username = $this->request->post("username");
        $email = $this->request->post("email");
        $password = $this->request->post("password");
        $confirm = $this->request->post("confirm");
        $address = $this->request->post("address");
        $phone = $this->request->post("phone");
        $card = $this->request->post("card");

        if ($password != $confirm) {
            View::render("Register", [
                "error" => "Password doesn't match"
            ]);
            return;
        }

        $user = new UserModel();
        $user->setName($name);
        $user->setEmail($email);
        $user->setUsername($username);

        if ($user->checkUserExists()) {
            View::render("Register", [
                "error" => "User already exists"
            ]);
            return;
        }

        $user->setPassword($password);
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setAvatar("default.jpg");
        $user->setCard($card);
        $user->insert();

        $session = new Session();
        $session->setSession($user->getId(), $user->getUsername());

        View::redirect("/search");
    }


}