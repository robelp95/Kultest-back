<?php

namespace App\Controller;


use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MenuController extends AbstractController
{
    /**
     * @Rest\Get("/menu/{menuName}", name="find_menu", requirements={"menuName"="^[a-zA-Z0-9]{3,255}$"})
     * @Rest\View(serializerGroups={"menu"}, serializerEnableMaxDepthChecks=true)
     * @param string $menuName
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getMenuByName(string $menuName, UserRepository $userRepository){

        $user = $userRepository->findOneBy(array("brandName" => $menuName));
        if (!$user) return $this->json([
            "success" => false,
            "data"=>null,
            "error"=> "not found"
        ], 404);
        if(count($user->getMenu()->getEnabledProducts()) == 0) return $this->json(null, 404);
        //User not suscribed
        if(!$user->getStatus()) return $this->json(null, 404);
        return $user;
    }

    /**
     * @Rest\Get(path="/menu/{menuName}/available", name="is_menu_name_available")
     * @param string $menuName
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function isMenuNameAvailable(string $menuName, UserRepository $userRepository){
        $user = $userRepository->findOneBy(array("brandName" => $menuName));

        return $this->json([
            "success" => true,
            "data" => !$user
        ], 200);
    }
}