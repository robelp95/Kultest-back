<?php


namespace App\Form\Model;


use App\Entity\User;

class UserDto
{
    public $id;
    public $address;
    public $base64Image;
    public $brandName;
    public $categoryId;
    public $category;
    public $description;
    public $deliveryCharge;
    public $email;
    public $image;
    public $minDelivery;
    public $name;
    public $open;
    public $opening;
    public $paymentInstructions;
    public $username;
    public $createdAt;
    public $updatedAt;
    public $userCoin;
    public $orderViaId;
    public $phoneNumber;

    public static function createFromUser(User $user)
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->address = $user->getAddress();
        $dto->brandName =$user->getBrandName();
        $dto->category= $user->getCategory();
        $dto->categoryId = $user->getCategory()->getId();
        $dto->deliveryCharge = $user->getDeliveryCharge();
        $dto->description = $user->getDescription();
        $dto->email = $user->getEmail();
        $dto->image = $user->getImage();
        $dto->minDelivery = $user->getMinDelivery();
        $dto->name = $user->getName();
        $dto->open = intval($user->getOpen()) ?? 0;
        $dto->opening = $user->getOpening();
        $dto->orderViaId = $user->getOrderVia()->getId();
        $dto->paymentInstructions = $user->getPaymentInstructions();
        $dto->phoneNumber = intval($user->getPhoneNumber());
        $dto->userCoin = $user->getUserCoin()->getId();
        $dto->username = $user->getPaymentInstructions();
        return $dto;
    }

    public static function createEmpty()
    {
        return new self();
    }
}