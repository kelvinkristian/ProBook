<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 24/10/18
 * Time: 22:38
 */

require_once "model/BaseModel.php";

class ReviewModel extends BaseModel
{
    protected $comment;
    protected $star;

    public function __construct()
    {
        parent::__construct("review");
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getStar()
    {
        return $this->star;
    }

    /**
     * @param mixed $star
     */
    public function setStar($star)
    {
        $this->star = $star;
    }


}