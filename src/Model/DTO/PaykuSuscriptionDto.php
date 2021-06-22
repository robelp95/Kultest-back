<?php


namespace App\Model\DTO;


class PaykuSuscriptionDto
{

    /**
     * "status": "register",
    "id": "sucaab7865dceaff49d8b3",
    "url": "http://BASE_URL/gateway/registrosuscripcion?tipoplan=2&plan=true&token=219&validacion=e6c50ba0e0"
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @param mixed $planId
     */
    public function setPlanId($planId): void
    {
        $this->planId = $planId;
    }

    public static function createFromArray(array $data)
    {

        $dto = new self();
        $dto->setId($data["id"]);
        $dto->setUrl($data["url"]);
        $dto->setStatus(false);
        $dto->setClientId($data["clientId"]);
        $dto->setPlanId($data["planId"]);
        return $dto;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    private $status;
    private $url;
    private $clientId;

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getPlanId()
    {
        return $this->planId;
    }
    private $planId;
    public function __construct()
    {
    }
}