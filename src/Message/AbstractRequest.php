<?php

namespace Ampeco\OmnipayKapitalbank\Message;

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

    public function setMerchant($value): void
    {
        $this->setParameter('Merchant', $value);
    }

    public function getMerchant(): string
    {
        return $this->getParameter('Merchant');
    }

    public function getAmount(): string
    {
        return number_format(parent::getAmount(), 2, '', '');
    }

    public function setApproveUrl($value): void
    {
        $this->setParameter('ApproveURL', $value);
    }

    public function getApproveUrl(): string
    {
        return $this->getParameter('ApproveURL');
    }

    public function setCancelUrl($value): void
    {
        $this->setParameter('CancelURL', $value);
    }

    public function getCancelUrl(): string
    {
        return $this->getParameter('CancelURL');
    }

    public function setDeclineUrl($value): void
    {
        $this->setParameter('DeclineURL', $value);
    }

    public function getDeclineUrl(): string
    {
        return $this->getParameter('DeclineURL');
    }

    public function setMerchantCertificate($value): void
    {
        $this->setParameter('MerchantCertificate', $value);
    }

    public function getMerchantCertificate(): string
    {
        return $this->getParameter('MerchantCertificate');
    }

    public function setMerchantKey($value): void
    {
        $this->setParameter('MerchantKey', $value);
    }

    public function getMerchantKey(): string
    {
        return $this->getParameter('MerchantKey');
    }

    public function getLanguage(): string
    {
        return strtoupper($this->getParameter('Language'));
    }

    public function setLanguage($value): void
    {
        $this->setParameter('Language', $value);
    }

    public function getSenderCardUID(): string
    {
        return $this->getParameter('SenderCardUID');
    }

    public function setSenderCardUID($value): void
    {
        $this->setParameter('SenderCardUID', $value);
    }

    public function setSessionId($value): void
    {
        $this->setParameter('SessionId', $value);
    }

    public function getSessionId(): string
    {
        return $this->getParameter('SessionId');
    }

    public function setOrderId($value): void
    {
        $this->setParameter('OrderId', $value);
    }

    public function getOrderId(): string
    {
        return $this->getParameter('OrderId');
    }

    public function setEci($value): void
    {
        $this->setParameter('eci', $value);
    }

    public function getEci(): int
    {
        return $this->getParameter('eci');
    }

    public function setOrderType($value): void
    {
        $this->setParameter('OrderType', $value);
    }

    public function getOrderType(): string
    {
        return $this->getParameter('OrderType');
    }

    public function setName($value): void
    {
        $this->setParameter('Name', $value);
    }

    public function getName(): string
    {
        return $this->getParameter('Name');
    }

    public function setTitle($value): void
    {
        $this->setParameter('Title', $value);
    }

    public function getTitle(): string
    {
        return $this->getParameter('Title');
    }

    protected function constructDataPayload(array $data, string $payload): array
    {
        return array_merge($data, ['payload' => $payload]);
    }
}
