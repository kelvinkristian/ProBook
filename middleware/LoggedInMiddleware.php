<?php
/**
 * Created by PhpStorm.
 * User: Gabriel B.R
 * Email: gabriel.bentara@gmail.com
 * Date: 23/10/18
 * Time: 8:47 AM
 */

include "middleware/BaseMiddleware.php";
require_once "core/Session.php";

class LoggedInMiddleware extends BaseMiddleware
{
    public static function execute(IRequest $request)
    {
        $session = new Session();
        if (!$session->inSession()) {
            header("Location: /login");
            return True;
        }

        return False;
    }
}