<?php


namespace App\Form\Model;

use App\Entity\Product;

class ProductDto
{
    public $id;
    public $name;
    public $description;
    public $base64Image;
    public $image;
    public $price;
    public $enabled;
    public $category;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getBase64Image()
    {
        return $this->base64Image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    public static function createFromProduct(Product $product): self
    {
        $dto = new self();
        $dto->id = $product->getId();
        $dto->name = $product->getName();
        $dto->category = $product->getCategory();
        $dto->description = $product->getDescription();
        $dto->price = $product->getPrice();
        $dto->enabled = intval($product->getEnabled()) ?? 0;
        $dto->image = $product->getImage();
        return $dto;
    }
}