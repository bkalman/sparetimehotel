<?php


namespace app\model;
use db\Database;

class MenuAllergens
{
    private $menu_id;
    private $allergen_id;

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
    public function getAllergenId()
    {
        return $this->allergen_id;
    }
}