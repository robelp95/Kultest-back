<?php


namespace App\Model\DTO;

class PaykuCreatePlanDto
{

    private $name;
    private $paykuId;


    /**
     * PaykuCreatePlanDto constructor.
     * @param $name
     * @param $paykuId
     */
    public function __construct($name, $paykuId)
    {
        $this->name = $name;
        $this->paykuId = $paykuId;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPaykuId()
    {
        return $this->paykuId;
    }

    /**
     * @param mixed $paykuId
     */
    public function setPaykuId($paykuId): void
    {
        $this->paykuId = $paykuId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

}