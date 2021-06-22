<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\Model\OrderDto;
use App\Form\Model\OrderProductDto;
use App\Form\Type\OrderFormType;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/users/{id}/orders", name="get_user_orders", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"order"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     * @param int $id
     * @return Order[]
     */
    public function getUserOrders(Request $request,
                                  OrderRepository $orderRepository,
                                  UserRepository $userRepository,
                                  int $id){

        if (!$userRepository->find($id)) return new Response('user not found', Response::HTTP_BAD_REQUEST);

        return $orderRepository->findAll();
    }

    /**
     * @Rest\Post(path="/users/{id}/orders", name="create_user_order", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"order"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param int $id
     * @return Order|FormInterface|Response
     */
    public function createOrder(Request $request,
                                OrderRepository $orderRepository,
                                EntityManagerInterface $em,
                                UserRepository $userRepository,
                                int $id){

        $orderDto = new OrderDto();

        $form = $this->createForm(OrderFormType::class, $orderDto);
        $form->handleRequest($request);

        $user = $userRepository->find($id);
        if (!$user) return new Response('user not found', Response::HTTP_BAD_REQUEST);

        if (!$form->isSubmitted()) return new Response('form not submitted', Response::HTTP_BAD_REQUEST);

        if ($form->isSubmitted() && $form->isValid()){

            $order = Order::create(
                $orderDto->getClient(),
                $orderDto->getContact(),
                $orderDto->getOrderNumber(),
                $orderDto->getTotal(),
                $orderDto->getType(),
                $user);

            foreach ($orderDto->getProducts() as $product){
                $orderProductDto = OrderProductDto::createFromOrderProductArray($product);

                $newProduct = new OrderProduct();
                $newProduct->setName($orderProductDto->getName());
                $newProduct->setPrice($orderProductDto->getPrice());
                $newProduct->setQuantity($orderProductDto->getQuantity());
                $order->addProduct($newProduct);
                $em->persist($newProduct);
            }
            $em->persist($order);
            $em->flush();
            return $order;
        }
        return $form;

    }
}
