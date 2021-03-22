<?php


namespace app\model;
use db\Database;

class Recommendation
{
    private $recommendation_id;
    private $title;

    /**
     * @return mixed
     */
    public function getRecommendationId()
    {
        return $this->recommendation_id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}