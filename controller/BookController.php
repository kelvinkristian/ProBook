<?php

require "BaseController.php";
require "core/View.php";
require "model/BookModel.php";

class BookController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function detail()
    {
        $vars = [
            "navbar" => "browse"
        ];

        $bookId = $this->request->param("id");
        $client = new SoapClient("http://127.0.0.1:9999/ws/search?wsdl");
        $result = $client->__soapCall("searchBook", [
            'parameters' => [
                "arg0" => $bookId
            ]
        ]);

        $model = new BookModel();
        $model->setBookId($bookId);
        $model->loadById();

        if ($model->getId() == 0) {
            $model->setRating(0);
            $model->setVote(0);
            $model->insert();
        }

        $result = $result->return;

        if (is_array($result)) {
            $result = $result[0];
        }

        $vars["book"] = [
            'id' => $model->getId(),
            'name' => $result->title,
            'author' => is_array($result->authors) ? join(', ', $result->authors) : $result->authors,
            'rating' => $model->getRating(),
            'vote' => $model->getVote(),
            'description' => $result->description,
            'imgsrc' => $result->imageLink,
            'price' => $result->price
        ];

        $result = $client->__soapCall("searchBookByGenre", [
            'parameters' => [
                "arg0" => $result->genres
            ]
        ]);

        $result = $result->return;

        $vars["review"] = $model->getBookReviews();

        $model = new BookModel();
        $model->setBookId($result->id);
        $model->loadById();

        if ($model->getId() == 0) {
            $model->setRating(0);
            $model->setVote(0);
            $model->insert();
        }

        $vars["book_rec"] = [
            'id' => $model->getId(),
            'book_id' => $model->getBookId(),
            'name' => $result->title,
            'author' => is_array($result->authors) ? join(', ', $result->authors) : $result->authors,
            'rating' => $model->getRating(),
            'vote' => $model->getVote(),
            'description' => $result->description,
            'imgsrc' => $result->imageLink
        ];

        View::render("detail", $vars);
    }
}