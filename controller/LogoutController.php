<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 23/10/18
 * Time: 10:56
 */

require "controller/BaseController.php";
require "core/View.php";
require_once "core/Session.php";

class LogoutController extends BaseController
{
    public function __construct(IRequest $request)
    {
        parent::__construct($request);
    }

    public function logout() {
        $session = new Session();
        $session->destroy();

        View::redirect("/login");
    }
}
