<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\CommonParameters;
use Ampeco\OmnipayKapitalBank\Gateway;
use DOMException;
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

        $merchantCertificate = $this->getMerchantCertificate();
        $certFile = tempnam(sys_get_temp_dir(), "cert_");
        file_put_contents($certFile, $merchantCertificate);

        $merchantKey = $this->getMerchantKey();
        $keyFile = tempnam(sys_get_temp_dir(), "cert_");
        file_put_contents($keyFile, $merchantKey);

        $ch = curl_init();
        $header = array("Content-Type: text/html; charset=utf-8");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
        curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data['payload']);
        curl_setopt($ch, CURLOPT_POST, true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode(json_encode(simplexml_load_string($output)), true);
        return $this->createResponse($response, $statusCode);
    }

    protected function constructDataPayload(array $data, string $payload): array
    {
        return array_merge($data, ['payload' => $payload]);
    }
}
