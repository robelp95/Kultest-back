<?php


namespace App\Service\Payku;


use App\Enum\PaykuConsts;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaykuGetPlans
{
    private string $paykuPrivateToken;
    private string $paykuPublicToken;
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param string $paykuPrivateToken
     * @param string $paykuPublicToken
     * @param HttpClientInterface $httpClient
     */
    public function __construct(string $paykuPrivateToken,
                                string $paykuPublicToken,
                                HttpClientInterface $httpClient) {
        $this->paykuPrivateToken = $paykuPrivateToken;
        $this->paykuPublicToken = $paykuPublicToken;
        $this->httpClient = $httpClient;
    }

    public function __invoke()
    {
        //Retry three times by default
        $newClient = new RetryableHttpClient($this->httpClient);
        $data = [];
        $utils = new PaykuUtils($this->paykuPrivateToken);

        $sign = $utils->signRequest(PaykuConsts::CREATE_PLAN_ENDPOINT, $data);
        $paykuPlans = null;
        try {
            $response = $newClient->request('GET', PaykuConsts::API_BASE . PaykuConsts::GET_PLANS_ENDPOINT,
                [
                    'headers' => [
                        'Sign' => $sign,
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken
                    ]
                ]);
            $paykuPlans = $response->toArray();
        } catch (\Exception $e) {
            throw $e;
        }
        return $paykuPlans;
    }
}