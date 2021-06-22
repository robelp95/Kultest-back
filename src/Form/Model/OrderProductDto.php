<?php


namespace App\Form\Model;


class OrderProductDto
{

    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    private $quantity;
    private $price;

    public static function createFromOrderProductArray($product)
    {
        $dto = new self();
        $dto->name = $product->getName();
        $dto->price = $product->getPrice();
        $dto->quantity = $product->getQuantity();
        return $dto;
    }


}