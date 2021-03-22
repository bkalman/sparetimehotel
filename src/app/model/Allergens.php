<?php


namespace app\model;
use db\Database;

class Allergens
{
    private $allergen_id;
    private $name;

    /**
     * @return mixed
     */
    public function getAllergenId()
    {
        return $this->allergen_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}