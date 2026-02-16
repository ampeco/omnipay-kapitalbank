<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\Modules\Monitoring\Http\MonitoredOmnipayHttpClient;
use Ampeco\OmnipayKapitalbank\CommonParameters;
use Ampeco\OmnipayKapitalBank\Gateway;
use DOMException;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Omnipay\Common\Http\Client as OmnipayHttpClient;
use Omnipay\Common\Message\AbstractRequest as OmniPayAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
abstract class AbstractRequest extends OmniPayAbstractRequest
{
    use CommonParameters;

    protected const API_URL_TEST = 'https://tstpg.kapitalbank.az:5443';
    protected const API_URL_PROD = 'https://3dsrv.kapitalbank.az:5443';

    protected ?Gateway $gateway;

    abstract protected function createResponse(array $data, int $statusCode): Response;

    public function getEndpoint(): string
    {
        return '/Exec';
    }

    public function setGateway(Gateway $gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->getTestMode() ? self::API_URL_TEST : self::API_URL_PROD;
    }

    public function getRequest(): string
    {
        return 'POST';
    }

    public function getData(): array
    {
       return [
         'merchant' => $this->getMerchant(),
       ];
    }

    /**
     * @throws DOMException
     */
    public function sendData($data): ResponseInterface|Response
    {
        $url = $this->getBaseUrl() . $this->getEndpoint();

        $certFile = tempnam(sys_get_temp_dir(), "cert_");
        file_put_contents($certFile, $this->getMerchantCertificate());

        $keyFile = tempnam(sys_get_temp_dir(), "cert_");
        file_put_contents($keyFile, $this->getMerchantKey());

        try {
            $guzzle = new GuzzleClient([
                'cert' => $certFile,
                'ssl_key' => $keyFile,
                'verify' => false,
                'allow_redirects' => true,
            ]);
            $innerClient = new OmnipayHttpClient(new GuzzleAdapter($guzzle));
            $client = new MonitoredOmnipayHttpClient($innerClient, 'KapitalBank');

            $httpResponse = $client->request(
                'POST',
                $url,
                ['Content-Type' => 'text/html; charset=utf-8'],
                $data['payload'],
            );
        } finally {
            @unlink($certFile);
            @unlink($keyFile);
        }

        $response = json_decode(json_encode(simplexml_load_string($httpResponse->getBody()->getContents())), true);
        return $this->createResponse($response, $httpResponse->getStatusCode());
    }

    protected function constructDataPayload(array $data, string $payload): array
    {
        return array_merge($data, ['payload' => $payload]);
    }
}
