<?php


namespace App\Model\DTO;

class PaykuClientDto
{
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPaykuId()
    {
        return $this->paykuId;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    private $name;
    private $paykuId;
    private $phone;
    private $status;

    /**
     * PaykuClientDto constructor.
     * @param $email
     * @param $name
     * @param $paykuId
     * @param $phone
     * @param $status
     */
    public function __construct($paykuId, $email, $name, $phone, $status)
    {
        $this->email = $email;
        $this->name = $name;
        $this->paykuId = $paykuId;
        $this->phone = $phone;
        $this->status = $status;
    }

    /**
     * {
    "status": "active",
    "id": "cl0be4c8e623c167bc8b29",
    "rut": "11111111",
    "name": "Joe Doe",
    "phone": "923122312",
    "email": "support@youwebsite.cl",
    "address": "Moneda 101",
    "country": "Chile",
    "region": "Metropolitana",
    "city": "Santiago",
    "postal_code": "850000",
    "created_at": "2020-09-29",
    "update_at": null,
    "subcriptions": null
    }
     */
    public static function create(){

    }

}