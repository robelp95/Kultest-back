<?php


namespace App\Service\Payku;


use App\Enum\PaykuConsts;
use App\Model\DTO\PaykuSuscriptionDto;
use App\Model\Exception\PaykuException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaykuCreateSuscription
{
    private string $paykuPrivateToken;
    private string $paykuPublicToken;
    private HttpClientInterface $client;
    public function __construct(string $paykuPrivateToken,
                                string $paykuPublicToken,
                                HttpClientInterface $client)
    {
        $this->paykuPrivateToken = $paykuPrivateToken;
        $this->paykuPublicToken = $paykuPublicToken;
        $this->client = $client;
    }

    public function __invoke(PaykuSuscriptionDto $paykuSuscriptionDto)
    {

        $sus = ['plan' => $paykuSuscriptionDto->getPlanId(), 'client' => $paykuSuscriptionDto->getClientId()];
        $utils = new PaykuUtils($this->paykuPrivateToken);
        $sign = $utils->signRequest(PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT, $sus);
        $endpoint = PaykuConsts::API_BASE . PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT;
        $response = null;
        try {
            $response = $this->client->request('POST', $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken,
                        'Sign' => $sign
                    ],
                    'json' => $sus
                ]);
            $data = $response->toArray();
            if (!isset($data["id"])) throw new PaykuException('Cannot create suscription');
        } catch (\Exception $e) {
            throw $e;
        }
        $data["planId"] = $paykuSuscriptionDto->getPlanId();
        $data["clientId"] = $paykuSuscriptionDto->getClientId();
        return PaykuSuscriptionDto::createFromArray($data);
    }

}