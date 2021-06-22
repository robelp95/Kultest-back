<?php


namespace App\Service\Payku;


use App\Entity\Suscription;
use App\Enum\PaykuConsts;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaykuDeleteSuscription
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

    public function __invoke(Suscription $suscription)
    {
        $sus = ['id' => $suscription->getPaykuId()];
        $utils = new PaykuUtils($this->paykuPrivateToken);
        $sign = $utils->signRequest(PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT, $sus);
        $endpoint = PaykuConsts::API_BASE . PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT . '/' . $suscription->getPaykuId();
        $response = null;
        $data = null;
        try {
            $response = $this->client->request('DELETE', $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken,
                        'Sign' => $sign
                    ]
                ])->toArray();
        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }

}