<?php


namespace App\Service;


use App\Entity\Product;
use App\Form\Model\ProductDto;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\Product\GetProduct;
use Symfony\Component\Form\FormFactoryInterface;

class ProductFormProcessor
{
    private GetProduct $getProduct;
    private ProductRepository $productRepository;
    private FormFactoryInterface $formFactory;


    public function __construct(
        GetProduct $getProduct,
        ProductRepository $productRepository,
        FormFactoryInterface $formFactory
    ) {
        $this->getProduct = $getProduct;
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $menuId = null): array
    {
        $product = null;
        $productDto = null;

        if ($menuId === null) {
            $productDto = new ProductDto();
        } else {
            $product = ($this->getProduct)($menuId);
            $productDto = ProductDto::createFromProduct($product);
        }

        $form = $this->formFactory->create(ProductFormType::class, $productDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        if ($product === null) {
            $product = Product::create(
                $productDto->getName()
            );
        } else {
            $product->update(
                $productDto->getName()
            );
        }

        $this->productRepository->save($product);
        return [$product, null];
    }

}