<?php


namespace App\Service\Payku;


class Plan
{
    private $name;
    private $description;
    private $paykuId;
    private $urlNotifyPayment;
    private $urlNotifySuscription;

    /**
     * PlanDto constructor.
     * @param $name
     * @param $description
     * @param $paykuId
     * @param $urlNotifyPayment
     * @param $urlNotifySuscription
     */
    public function __construct($name,
                                $description,
                                $paykuId,
                                $urlNotifyPayment,
                                $urlNotifySuscription)
    {
        $this->name = $name;
        $this->description = $description;
        $this->paykuId = $paykuId;
        $this->urlNotifyPayment = $urlNotifyPayment;
        $this->urlNotifySuscription = $urlNotifySuscription;
    }

}