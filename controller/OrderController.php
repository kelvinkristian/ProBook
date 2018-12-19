<?php

require_once "BaseController.php";
require_once "core/View.php";
require_once "core/Session.php";
require_once "model/OrderModel.php";
require_once "model/BookModel.php";
require_once "model/UserModel.php";

class OrderController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function new()
    {
        $order_count = (int)$this->request->post("order_count");
        $book_id = (int)($this->request->post("book_id"));
        $otp = $this->request->post("otp");

        $session = new Session();
        $user_id = (int)$session->inSession();

        $book = new BookModel();
        $book->setId($book_id);
        $book->load();

        $user = new UserModel();
        $user->setId($user_id);
        $user->load();


        $client = new SoapClient("http://127.0.0.1:9999/ws/search?wsdl");
        $result = $client->__soapCall("orderBook", [
            'parameters' => [
                "arg0" => $book->getBookId(),
                "arg1" => $order_count,
                "arg2" => $user->getCard(),
                "arg3" => $otp
            ]
        ]);

        if (!$result->return->success) {
            echo json_encode(['status' => 'error', 'order_id' => -1, 'message' => $result->return->reason]);
            return;
        }

        $order = new OrderModel();
        $order->setAmount($order_count);
        $order->setBookId($book_id);
        $order->setUserId($user_id);
        $order->setCreatedAt(date("Y/m/d H:i:s"));
        $order->insert();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'order_id' => (int)$order->getLastInsertId()]);
    }
}