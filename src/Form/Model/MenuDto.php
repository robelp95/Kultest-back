<?php


namespace App\Form\Model;

use App\Entity\Menu;

class MenuDto
{
    public $id;
    public array $products;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function __construct()
    {
        $this->products = [];
    }

    public static function createFromMenu(Menu $menu): self
    {
        $dto = new self();
        $dto->id = $menu->getId();
        $dto->products = [];
        return $dto;
    }
}