<?php

namespace App\Service\Menu;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class MenuManager
{
    private EntityManagerInterface $em;
    private MenuRepository $menuRepository;
    public function __construct(
        EntityManagerInterface $em,
        MenuRepository $menuRepository
    )
    {
        $this->em = $em;
        $this->menuRepository = $menuRepository;
    }

    public function find($id){
        return $this->menuRepository->find($id);
    }
    public function create():Menu{
        return new Menu();
    }
    public function save($menu){

        $this->em->persist($menu);
        $this->em->flush();
        return $menu;
    }
    public function reload($menu){
        $this->em->refresh($menu);
        return $menu;

    }
    public function persist(Menu $menu)
    {
        $this->em->persist($menu);
        return $menu;
    }
}