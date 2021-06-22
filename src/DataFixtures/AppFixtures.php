<?php

namespace App\DataFixtures;

use App\Entity\Coin;
use App\Entity\OrderVia;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


#Create dummy data for testing in DB
#https://symfony.com/doc/current/testing/database.html#dummy-data-fixtures
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $orderVia = new OrderVia();
        $orderVia->setName("whatsapp");
        $userCoin = new Coin();
        $userCoin->setName("$");
        $manager->persist($orderVia);
        $manager->persist($userCoin);

        $user = new User();
        $user->setName("Catalo de prueba");
        $user->setUsername("prueba");
        $user->setPaymentInstructions("Efectivo/Transferencia");
        $user->setCategory("Gastronomia");
        $user->setBrandName("menudeprueba");
        $user->setOpening("De 8 a 20hs");
        $user->setOpen(true);
        $user->setPassword("1234");
        $user->setDescription("Venta de comidas");
        $user->setOrderVia($orderVia);
        $user->setAddress("Calle falsa 123");
        $user->setUserCoin($userCoin);
        $user->setEmail("prueba@gmail.com");
        $user->setImage('');

        $manager->persist($user);

        $manager->flush();
    }
}
