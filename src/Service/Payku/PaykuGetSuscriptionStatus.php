<?php


namespace App\Service\Payku;


use App\Enum\PaykuConsts;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaykuGetSuscriptionStatus
{

    private $paykuPrivateToken;
    private $paykuPublicToken;
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct($paykuPrivateToken, $paykuPublicToken, HttpClientInterface $client)
    {
        $this->paykuPrivateToken = $paykuPrivateToken;
        $this->paykuPublicToken = $paykuPublicToken;
        $this->client = $client;
    }

    public function __invoke($suscriptionId)
    {
        //Retry three times by default
        $newClient = new RetryableHttpClient($this->client);
        $data = ['id' => $suscriptionId];
        $utils = new PaykuUtils($this->paykuPrivateToken);

        $sign = $utils->signRequest(PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT, $data);
        $paykuPlan = null;
        try {
            $response = $newClient->request('GET', PaykuConsts::API_BASE . PaykuConsts::PAYKU_SUSCRIPTION_ENDPOINT . '/' . $suscriptionId,
                [
                    'headers' => [
                        'Sign' => $sign,
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken
                    ]
                ]);
            $paykuPlan = $response->toArray();
        } catch (\Exception $e) {
            print_r($e->getMessage() . ' - ' . $e->getTraceAsString());
            exit;
            throw $e;
        }

        return $paykuPlan["status"] ?? "inactive";
    }
}