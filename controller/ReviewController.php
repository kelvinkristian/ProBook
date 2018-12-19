<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 23/10/18
 * Time: 11:44
 */

require "controller/BaseController.php";
require "core/View.php";
require_once "core/Session.php";
require "model/OrderModel.php";
require "model/ReviewModel.php";

class ReviewController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function view()
    {
        $order = new OrderModel();
        $order->setId($this->request->param("order"));
        $order->load();

        $session = new Session();

        if ($order->getUserId() != $session->inSession()) {
            View::redirect("/search");
            return;
        }

        View::render("Review", ["order" => $order]);
    }

    public function review() {
        $order = new OrderModel();
        $order->setId($this->request->param("order"));
        $order->load();

        $session = new Session();

        if ($order->getUserId() != $session->inSession()) {
            View::redirect("/search");
            return;
        }

        if ($order->getReviewId() != null) {
            View::render("Review", ["error" => "You have reviewed this order", "order" => $order]);
            return;
        }

        $star = $this->request->post("star");

        if ($star == 0) {
            View::render("Review", ["error" => "You haven't rated", "order" => $order]);
            return;
        }

        $comment = $this->request->post("comment");
        if ($comment == "") {
            View::render("Review", ["error" => "You haven't entered a comment", "order" => $order]);
            return;
        }

        $review = new ReviewModel();
        $review->setComment($comment);
        $review->setStar($star);
        $review->insert();
        $order->setReviewId($review->getId());
        $order->save();

        $book = new BookModel();
        $book->setId($order->getBookId());
        $book->recalculateRating();

        View::redirect("/history");
    }
}