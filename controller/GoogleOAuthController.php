<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 11/29/2018
 * Time: 10:17 PM
 */

require "controller/BaseController.php";
require "core/View.php";
require_once "model/UserModel.php";
require_once "core/Session.php";

class GoogleOAuthController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function oauth() {
        $id = $this->request->post("id");
        $name = $this->request->post("name");
        $email = $this->request->post("email");
        $img = $this->request->post("img");
        $arr = explode("@", $email);

        $user = new UserModel();
        $user->setName($name);
        $user->setUsername($arr[0]);
        $user->setEmail($email);
        $user->setPassword($id);
        $user->setAvatar($img);

        if ($user->checkUserExists()) {
            //login
            $user->loadFromUserPass();
        } else {
            $user->setAddress("Jalan Kenangan");
            $user->setPhone("082180367208");
            $user->setCard('1111222233334444');
            $user->insert();
        }

        $session = new Session();
        $session->setSession($user->getId(), $user->getUsername());

        View::redirect("/search");
    }
}