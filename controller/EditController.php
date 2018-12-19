<?php
/**
 * Created by PhpStorm.
 * User: shintaayuck
 * Date: 24/10/18
 * Time: 13.50
 */

require "controller/BaseController.php";
require "core/View.php";
require "model/UserModel.php";

class EditController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function view()
    {
        $model = new UserModel();
        $session = new Session();
        $model->setId((int)$session->inSession());
        $model->load();

        $vars = [
            "navbar" => "profile",
            "avatar" => $model->getAvatar(),
            "name" => $model->getName(),
            "address" => $model->getAddress(),
            "phone" => $model->getPhone(),
            "card_number" => $model->getCard()
        ];

        View::render("edit", $vars);
    }

    public function editprofile()
    {
        $name = $this->request->post("name");
        $address = $this->request->post("address");
        $phone = $this->request->post("phone");
        $card_number = $this->request->post("card");
        $avatar = $this->request->files["avatar"];

        $user = new UserModel();
        $session = new Session();
        $user->setId((int)$session->inSession());
        $user->load();

        $newUser = new UserModel();
        $newUser->setId($user->getId());
        $newUser->setUsername($user->getUsername());
        $newUser->setEmail($user->getEmail());
        $newUser->setPassword($user->getPassword());


        if ($name != "") {
            $newUser->setName($name);
        } else {
            $newUser->setName($user->getName());
        }

        if ($address != "") {
            $newUser->setAddress($address);
        } else {
            $newUser->setAddress($user->getAddress());
        }

        if ($phone != "") {
            $newUser->setPhone($phone);
        } else {
            $newUser->setPhone($user->getPhone());
        }

        if ($card_number != "") {
            $newUser->setCard($card_number);
        } else {
            $newUser->setCard($user->getCard());
        }
        
        if ($avatar["size"] != 0) {
            $imageFileType = strtolower(pathinfo($avatar["name"],PATHINFO_EXTENSION));
            move_uploaded_file($avatar["tmp_name"], "static/".$newUser->getUsername().".".$imageFileType);
            $newUser->setAvatar($newUser->getUsername().".".$imageFileType);
        } else {
            $newUser->setAvatar($user->getAvatar());
        }

        $newUser->save();

        View::redirect('/profile');


    }


}
