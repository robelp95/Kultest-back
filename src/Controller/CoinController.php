<?php

namespace App\Controller;

use App\Entity\Coin;
use App\Repository\CoinRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class CoinController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/coins", name="coin")
     * @Rest\View(serializerGroups={"coin"}, serializerEnableMaxDepthChecks=true)
     * @param CoinRepository $coinRepository
     * @return Coin[]
     */
    public function index(CoinRepository $coinRepository)
    {
        return $coinRepository->findAll();
    }
}
