<?php


namespace App\Service\Payku;

/**
 * Class that contains payku conection data
 * Class Payku
 * @package App\Service\Payku
 */

class Payku
{
    /**
     * Private key
     * @var string
     */
    private string $apiKey;
    private string $apiBase = "https://des.payku.cl";

    /**
     * @return string
     */
    public function getApiBase(): string
    {
        return $this->apiBase;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
    public function __construct(string $apiKey, string $apiBase)
    {
        $this->apiKey = $apiKey;
        $this->apiBase = $apiBase;
    }

}