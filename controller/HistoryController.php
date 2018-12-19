<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 24/10/18
 * Time: 20:59
 */

require "BaseController.php";
require "core/View.php";
require_once "core/Session.php";
require "model/BookModel.php";
require "model/OrderModel.php";

class HistoryController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function view()
    {
        $vars = [
            "navbar" => "history"
        ];
        $session = new Session();

        $order = new OrderModel();
        $order->setUserId($session->inSession());
        $orders = $order->searchByUserId();

        $vars["orders"] = array_reverse($orders);

        View::render("history", $vars);
    }
}
