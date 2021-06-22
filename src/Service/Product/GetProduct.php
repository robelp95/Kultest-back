<?php


namespace App\Service\Product;


use App\Entity\Product;
use App\Model\Exception\Product\ProductNotFound;
use App\Repository\ProductRepository;

class GetProduct
{

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(string $id): ?Product
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            ProductNotFound::throwException();
        }
        return $product;
    }
}