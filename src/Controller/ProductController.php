<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Model\ProductDto;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    /**
     * @Rest\Get(path="/products", name="get_products")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     * @param ProductRepository $productRepository
     * @return Product[]
     */
    public function getProducts(ProductRepository $productRepository){
        return $productRepository->findAll();
    }

    /**
     * @Rest\Post("/users/{userId}/products", name="create_user_product")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param integer $userId
     * @param UserRepository $userRepository
     * @param FileUploader $fileUploader
     * @return JsonResponse
     */

    public function createProduct(Request $request, $userId,
                                  UserRepository $userRepository,
                                  FileUploader $fileUploader){

        $em = $this->getDoctrine()->getManager();

        $productDto = new ProductDto();
        $form = $this->createForm(ProductFormType::class, $productDto);

        if (!$userRepository->find($userId)) return $this->json([
            "success" => false, "data" => null, "error" => "User not found"
        ], 404);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $menu = $userRepository->findOneBy(array("id" =>$userId))->getMenu();
            $product = new Product();
            if ($productDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($productDto->base64Image, 'product_');
                $product->setImage($filename);
            }

            $product->setName($productDto->name);
            $product->setCategory($productDto->category);
            $product->setDescription($productDto->description);
            $product->setEnabled($productDto->enabled);
            $product->setPrice($productDto->price);
            $menu->addProduct($product);
            $em->persist($menu);
            $em->flush();
            return $product;
        }
        return $form;
    }

    /**
     * @Rest\Get(path="/products/{id}", name="find_product", methods={"GET"})
     * @Rest\View(serializerGroups={"product"})
     * @param $id
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function getProductById($id, ProductRepository $productRepository){
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
        return $this->json([
            "success" => true,
            "data" =>$productRepository->findOneBy(array("id" => $id))
        ], 200);
    }

    /**
     * @Rest\Get(path="/users/{id}/products", name="get_user_products")
     * @Rest\View(serializerGroups={"product"})
     * @param $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getUserProducts($id, UserRepository $userRepository){
        $user = $userRepository->find($id);
        if (!isset($user)) return $this->json([
            "success" => false, "data" => null, "error" => "User not found"
        ], 404);

        $products = $user->getMenu()->getProducts();
        return $this->json([
            "success" => true,
            "data" => $products
        ], 200);
    }
}
