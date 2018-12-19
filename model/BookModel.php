<?php

require_once "model/BaseModel.php";

class BookModel extends BaseModel {
    protected $id = 0;
    protected $rating = 0;
    protected $vote = 0;
    protected $book_id;

    public function __construct()
    {
        parent::__construct("book");
    }

    public function loadById() {
        $stmt = $this->conn->prepare("select * from book where book_id = :id limit 1;");
        $stmt->bindParam("id", $this->book_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result == null) {
            return;
        }
        foreach ($result as $column => $value) {
            $this->$column = $value;
        }
    }

    public function recalculateRating() {
        $count = 0;
        $total = 0;
        $this->load();
        $reviews = $this->getBookReviews();
        foreach($reviews as $r) {
            $count += 1;
            $total += (int)$r["star"];
        }
        $rating = 0;
        if($count > 0) {
            $rating = $total / $count;
        }
        $this->setRating($rating);
        $this->setVote($count);
        $this->save();
    }

    public function getBookReviews() {
        $stmt = $this->conn->prepare("select * from `order` inner join review on `order`.review_id = review.id inner join user on order.user_id = user.id where book_id like :id;");
        $stmt->bindParam("id", $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @param mixed $book_id
     */
    public function setBookId($book_id)
    {
        $this->book_id = $book_id;
    }
}