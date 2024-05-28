<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalBank\Gateway;
use Ampeco\OmnipayKapitalbank\XmlBuilder;
use DOMException;
use Omnipay\Common\Message\AbstractRequest as OmniPayAbstractRequest;

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
    private $key;

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
        return self::API_URL_TEST;
//        return $this->getTestMode() ? self::API_URL_TEST : self::API_URL_PROD; // TODO
    }

    public function getRequest(): string
    {
        return 'POST';
    }

    public function getData()
    {
       return [
         'merchant' => $this->getMerchant(),
       ];
    }

    /**
     * @throws DOMException
     */
    public function sendData($data)
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

    public function setMerchant($value)
    {
        $this->setParameter('Merchant', $value);
    }

    public function getMerchant()
    {
        return $this->getParameter('Merchant');
    }

    public function getAmount()
    {
        return number_format(parent::getAmount(), 2, '', '');
    }

    public function setApproveUrl($value)
    {
        $this->setParameter('ApproveURL', $value);
    }

    public function getApproveUrl()
    {
        return $this->getParameter('ApproveURL');
    }

    public function setCancelUrl($value)
    {
        $this->setParameter('CancelURL', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('CancelURL');
    }

    public function setDeclineUrl($value)
    {
        $this->setParameter('DeclineURL', $value);
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('DeclineURL');
    }

    public function setMerchantCertificate($value)
    {
        $this->setParameter('MerchantCertificate', $value);
    }

    public function getMerchantCertificate()
    {
        return $this->getParameter('MerchantCertificate');
    }

    public function setMerchantKey($value)
    {
        $this->setParameter('MerchantKey', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('MerchantKey');
    }

    public function getLanguage()
    {
        return strtoupper($this->getParameter('Language'));
    }

    public function setLanguage($value)
    {
        $this->setParameter('Language', $value);
    }

    public function getSenderCardUID()
    {
//        $this->key = 'Sender';
//        return strtoupper($this->getParameter('' . $this->key . 'CardUID'));
        return $this->getParameter('SenderCardUID');
    }

    public function setSenderCardUID($value)
    {
        $this->setParameter('SenderCardUID', $value);
    }

    public function setSessionId($value)
    {
        $this->setParameter('SessionId', $value);
    }

    public function getSessionId()
    {
        return $this->getParameter('SessionId');
    }

    public function setOrderId($value)
    {
        $this->setParameter('OrderId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('OrderId');
    }

    public function setEci($value)
    {
        $this->setParameter('eci', $value);
    }

    public function getEci()
    {
        return $this->getParameter('eci');
    }

    public function setOrderType($value)
    {
        $this->setParameter('OrderType', $value);
    }

    public function getOrderType()
    {
        return $this->getParameter('OrderType');
    }
}
