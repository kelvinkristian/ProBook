<?php

require "BaseController.php";
require "core/View.php";
require "model/BookModel.php";

class SearchController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function landing()
    {
        $vars = [
            "navbar" => "browse"
        ];

        if($this->request->get("query") === NULL) {
            View::render("search_angular", $vars);
        } else {
            $query = $this->request->get("query");

            $vars["query"] = $query;
            $model = new BookModel();
            $vars["result"] = $model->searchByKeyword($query);

            View::render("result", $vars);
        }
    }

    public function googleBookSearch()
    {
        $client = new SoapClient("http://127.0.0.1:9999/ws/search?wsdl");
        $result = $client->__soapCall("searchBook", [
            'parameters' => [
                "arg0" => $this->request->param('keyword')
            ]
        ]);

        $result = $result->return;
        if (!is_array($result)) {
            $result = [$result];
        }
        $json = [];
        foreach ($result as $item) {
            $book = new BookModel();
            $book->setBookId($item->id);
            $book->loadById();
            $item = (array)$item;
            $item['vote'] = $book->getVote();
            $item['rating'] = $book->getRating();
            array_push($json, $item);
        }

        echo json_encode($json);
    }
}
