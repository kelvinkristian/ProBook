<?php
/**
 * Created by PhpStorm.
 * User: Gabriel B.R
 * Email: gabriel.bentara@gmail.com
 * Date: 23/10/18
 * Time: 8:50 AM
 */

include "middleware/BaseMiddleware.php";
require_once "core/Session.php";

class NotLoggedInMiddleware extends BaseMiddleware
{
    public static function execute(IRequest $request)
    {
        $session = new Session();
        if ($session->inSession()) {
            header("Location: /");
            return True;
        }

        return False;
    }
}