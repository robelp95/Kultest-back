<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\CoinRepository;
use App\Repository\OrderViaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{

    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private CoinRepository $coinRepository;
    private CategoryRepository $categoryRepository;
    private OrderViaRepository $orderViaRepository;
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        CoinRepository $coinRepository,
        CategoryRepository $categoryRepository,
        OrderViaRepository $orderViaRepository

    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->coinRepository = $coinRepository;
        $this->orderViaRepository = $orderViaRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function findOrderVia($id){
        return $this->orderViaRepository->find($id);
    }
    public function findCategory($id){
        return $this->categoryRepository->find($id);
    }
    public function findDefaultCoin(){
        return $this->coinRepository->find(1);
    }
    public function findDefaultOrderVia(){
        return $this->orderViaRepository->find(1);
    }
    public function findDefaultCategory(){
        return $this->categoryRepository->find(1);
    }
    public function find($id){
        return $this->userRepository->find($id);
    }
    public function create():User{
        return new User();
    }
    public function save($user){

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
    public function reload($user){
        $this->em->refresh($user);
        return $user;

    }
}