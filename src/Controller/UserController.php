<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\Model\MenuDto;
use App\Form\Model\ProductDto;
use App\Form\Type\MenuFormType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Payku\PaykuCreateClient;
use App\Service\User\UpdateUserStatus;
use App\Service\User\UserFormProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractFOSRestController
{

    /**
     * @var PaykuCreateClient
     */
    private PaykuCreateClient $paykuCreateClient;

    public function __construct(PaykuCreateClient $paykuCreateClient)
    {
        $this->paykuCreateClient = $paykuCreateClient;
    }

    /**
     * @Rest\Post("/users", name="create_user")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserFormProcessor $userFormProcessor
     * @return View
     */
    public function createUser(Request $request,
                               UserRepository $userRepository,
                               UserFormProcessor $userFormProcessor
    )
    {
        $parameters = json_decode($request->getContent(), true);
        $userExists = $userRepository->findOneBy(array("email" => $parameters["email"] ?? "" )) != null;
        if ($userExists) return View::create('user already exists', Response::HTTP_BAD_REQUEST);

        try {
            [$user, $error ] =($userFormProcessor)($request);
            $statusCode = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $user ?? $error;
        }catch (\Exception $e){
            print_r($e->getMessage() . ' ' . $e->getTraceAsString());exit;
        }

        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post("/users/{id}", name="update_user_attributes", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserFormProcessor $userFormProcessor
     * @return User|FormInterface|JsonResponse|Response
     */
    public function updateUser(int $id,
                               Request $request,
                               UserRepository $userRepository,
                               UserFormProcessor $userFormProcessor)
    {
        $user = $userRepository->find($id);
        if (!$user) return $this->json(["success" => false, "data" => null,"error" => "User not found"], 400);

        [$user, $error] = ($userFormProcessor)($request, $user->getId());

        $statusCode = $user ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $user ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Get("/users", name="get_users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param UserRepository $userRepository
     * @return User[]
     */
    public function getUsers(UserRepository $userRepository){
        return $userRepository->findAll();
    }

    /**
     * @Rest\Get("/users/{id}", name="find_user", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     */

    public function getUserById(int $id, UserRepository $userRepository){
        $user = $userRepository->find($id);
        if (is_null($user)) return $this->json([
            "success" => false,
            "data" => null,
            "error"=> "not found"
        ], 400);
        return $user;
    }

    /**
     * @Rest\Get(path="/users/{email}", name="get_user_by_email")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param $email
     * @param UserRepository $userRepository
     * @param UpdateUserStatus $updateUserStatus
     * @return User|View
     */
    public function getUserbyEmail($email,
                                   UserRepository $userRepository,
                                   UpdateUserStatus $updateUserStatus){

        $user = $userRepository->findOneBy(array("email" => $email));

        if (!$user) {
            return View::create('user not found', Response::HTTP_NOT_FOUND);

        }

        ($updateUserStatus)($user->getId());

        return $userRepository->findOneBy(array("email" => $email));

        return $user;
    }

    /**
     * @Rest\Get("/users/{id}/products/{prodId}", name="get_user_product")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     * @param $id
     * @param $prodId
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function getuserProduct($id, $prodId, UserRepository $userRepository, ProductRepository $productRepository){
        $user = $userRepository->findOneBy(array("id" => $id));
        if (!isset($user)) return $this->json([
            "success" => false,
            "data" => null,
            "error" => "Not found"
        ], 404);
        $productFound = $user->getMenu()->getProducts()->filter(function ($prod) use ($prodId){
            return $prod->getId() == $prodId;
        })->first();
        return $productFound ?  $this->json([
            "success " => true,
            "data" => $productFound
        ], 200) :
        $this->json([
            "success" => false,
            "data" => null,
            "error" => "not found"
        ], 404);
    }

    /**
     * @Rest\Post("/users/{id}/menu", name="edit_user_menu", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"menu"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return FormInterface|JsonResponse
     */

    public function editUserMenu(int $id,
                                 EntityManagerInterface $em,
                                 UserRepository $userRepository,
                                 ProductRepository $productRepository,
                                 Request $request,
                                 FileUploader $fileUploader
    ){

        //TODO validate user modifying his own menu

        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(["success" => false, "data" => null,"error" => "User not found"], 400);
        }
        $data = json_decode($request->getContent(), true);
        $userMenu = $user->getMenu();
        $menuDto = MenuDto::createFromMenu($userMenu);
        $originalProducts = new ArrayCollection();


        foreach ($userMenu->getProducts() as $product){
            $productDto = ProductDto::createFromProduct($product);
            $menuDto->products[] = $productDto;
            $originalProducts->add(ProductDto::createFromProduct($product));
        }


        $form = $this->createForm(MenuFormType::class, $menuDto);
        $form->submit($data);

        if (!$form->isSubmitted()) return new Response('not submitted', Response::HTTP_BAD_REQUEST);

        if ($form->isValid()){


            //remove products
            foreach ($originalProducts as $originalProductDto){
                if (!in_array($originalProductDto, $menuDto->getProducts())){
                    $product = $productRepository->find($originalProductDto->id);
                    $userMenu->removeProduct($product);
                }
            }

            //add products
            foreach ($menuDto->getProducts() as $newProductDto){
                if (!$originalProducts->contains($newProductDto)){
                    $product = $productRepository->find($newProductDto->id ?? 0);

                    if(!$product){
                        $product = new Product();
                        if ($newProductDto->base64Image) {
                            $filename = $fileUploader->uploadBase64File($newProductDto->base64Image, 'product_');
                            $product->setImage($filename);
                        }
                        $product->setName($newProductDto->name);
                        $product->setDescription($newProductDto->description);
                        $product->setPrice($newProductDto->price);
                        $product->setEnabled($newProductDto->enabled);
                        $product->setCategory($newProductDto->category);

                    }else{
                        //Update product fields
                        $product->setName($newProductDto->name);
                        $product->setDescription($newProductDto->getDescription());
                        $product->setPrice($newProductDto->getPrice());
                        $product->setEnabled(intval($newProductDto->getEnabled()) ?? false);
                        $product->setCategory($newProductDto->getCategory());
                        if ($newProductDto->base64Image) {
                            $filename = $fileUploader->uploadBase64File($newProductDto->base64Image, 'product_');
                            $product->setImage($filename);
                        }
                    }

                    $em->persist($product);

                    $userMenu->addProduct($product);
                }
            }
            $em->persist($userMenu);
            $em->flush();
            $em->refresh($userMenu);;
            return $userMenu;
        }
        return $form;
    }
}
