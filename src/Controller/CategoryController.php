<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories", name="get_all_categories")
     * @Rest\View(serializerGroups={"category"}, serializerEnableMaxDepthChecks=true)
     * @param CategoryRepository $categoryRepository
     * @return Category[]
     */
    public function getCategories(CategoryRepository $categoryRepository){
        return $categoryRepository->findAll();
    }
}
