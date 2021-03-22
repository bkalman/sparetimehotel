<?php


namespace app\model;
use db\Database;

class MenuRecommendation
{
    private $menu_id;
    private $recommendation_id;

    /**
     * @return mixed
     */
    public function getMenuId()
    {
        return $this->menu_id;
    }

    /**
     * @return mixed
     */
    public function getRecommendationId()
    {
        return $this->recommendation_id;
    }
}