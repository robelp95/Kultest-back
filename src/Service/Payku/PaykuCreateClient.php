<?php
namespace App\Service\Payku;

use App\Enum\PaykuConsts;
use App\Model\DTO\PaykuClientDto;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PaykuCreateClient {

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

    /**
     * Creates a Stripe Product
     *
     * @param string $name
     * @param string $email
     * @param string $phone
     * @return PaykuClientDto
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(string $name,
                             string $email,
                             string $phone): PaykuClientDto
    {

        $client = ['name' => $name, 'email' => $email, 'phone' => $phone];

        $utils = new PaykuUtils($this->paykuPrivateToken);
        $sign = $utils->signRequest(PaykuConsts::PAYKU_CLIENT_ENDPOINT, $client);
        $endpoint = PaykuConsts::API_BASE . PaykuConsts::PAYKU_CLIENT_ENDPOINT;
        $response = null;
        try {
            $response = $this->httpClient->request('POST', $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->paykuPublicToken,
                        'Sign' => $sign
                    ],
                    'json' => $client
                ]);

        } catch (\Exception $e) {
            throw $e;
        }
        $data = $response->toArray();

        return new PaykuClientDto($data["id"], $data["email"], $data["name"], $data["phone"], $data["status"]);
    }

}