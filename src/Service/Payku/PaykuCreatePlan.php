<?php


namespace App\Service\Payku;



use App\Enum\PaykuConsts;
use App\Model\DTO\PaykuCreatePlanDto;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class PaykuCreatePlan
{

    private string $paykuPrivateToken;
    private string $paykuPublicToken;
    private HttpClientInterface $client;

    /**
     * paykuCreatePlan constructor.
     * @param string $paykuPrivateToken
     * @param string $paykuPublicToken
     * @param HttpClientInterface $client
     */
    public function __construct(string $paykuPrivateToken,
                                string $paykuPublicToken,
                                HttpClientInterface $client)
    {
        $this->paykuPrivateToken = $paykuPrivateToken;
        $this->paykuPublicToken = $paykuPublicToken;
        $this->client = $client;
    }

    public function __invoke($planName){

        //Retry three times by default
        $newClient = new RetryableHttpClient($this->client);
        $data = ['name' => $planName];
        $utils = new PaykuUtils($this->paykuPrivateToken);

        $sign = $utils->signRequest(PaykuConsts::CREATE_PLAN_ENDPOINT, $data);
        $paykuPlan = null;
        try {
            $response = $newClient->request('POST', PaykuConsts::API_BASE . PaykuConsts::CREATE_PLAN_ENDPOINT,
                [
                    'json' => [
                        'name' => $planName
                    ],
                    'headers' => [
                        'Sign' => $sign,
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken
                    ]
                ]);
            $paykuPlan = $response->toArray();
        } catch (\Exception $e) {
            throw $e;
        }

        return new PaykuCreatePlanDto($planName, $paykuPlan["id"]);

    }
}