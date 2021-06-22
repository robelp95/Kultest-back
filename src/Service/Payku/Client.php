<?php


namespace App\Service\Payku;


class Client
{
    private $name;
    private $email;
    private $phone;
    private $paykuId;
    private $createdAt;

    /**
     * Client constructor.
     * @param $name
     * @param $email
     * @param $phone
     * @param $paykuId
     * @param $createdAt
     */
    public function __construct($name, $email, $phone, $paykuId, $createdAt)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->paykuId = $paykuId;
        $this->createdAt = $createdAt;
    }

    /**
     * @param array $data
     * @return Client
     */
    public function createFromArray(array $data){

        $dto = new self();
        $dto->phone = $data["phone"];
        $dto->email = $data["email"];
        $dto->name = $data["name"];
        $dto->paykuId = $data["id"];
        $dto->createdAt = $data["created_at"];

        return $dto;
    }
}