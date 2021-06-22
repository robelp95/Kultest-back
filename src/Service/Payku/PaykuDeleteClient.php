<?php


namespace App\Service\Payku;


use App\Enum\PaykuConsts;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaykuDeleteClient
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

    public function __invoke($clientId)
    {
        $client = ['id' => $clientId];

        $utils = new PaykuUtils($this->paykuPrivateToken);
        $sign = $utils->signRequest(PaykuConsts::PAYKU_CLIENT_ENDPOINT . '/', $client);
        $endpoint = PaykuConsts::API_BASE . PaykuConsts::PAYKU_CLIENT_ENDPOINT . '/' . $clientId;
        $response = null;
        try {
            $response = $this->httpClient->request('DELETE', $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken,
                        'Sign' => $sign
                    ],
                    'json' => $client
                ])->toArray();

        } catch (\Exception $e) {
            throw $e;
        }
        return $response;
    }
}