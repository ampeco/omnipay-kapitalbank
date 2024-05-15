<?php

namespace Ampeco\OmnipayKapitalBank\Message;

use Ampeco\OmnipayKapitalBank\Gateway;
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
        return $this->getTestMode() ? self::API_URL_TEST : self::API_URL_PROD; //
    }

    public function getRequest(): string
    {
        return 'POST';
    }

    public function getData()
    {
        // TODO: Implement sendData() method.
    }

    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }

    public function setMerchant($value)
    {
        return $this->setParameter('Merchant', $value);
    }

    public function getMerchant()
    {
        return $this->getParameter('Merchant');
    }

    public function setAmount($value)
    {
        return $this->setParameter('Amount', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('Amount');
    }

    public function setApproveUrl($value)
    {
        return $this->setParameter('ApproveURL', $value);
    }

    public function getApproveUrl()
    {
        return $this->getParameter('ApproveURL');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('CancelURL', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('CancelURL');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('DeclineURL', $value);
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('DeclineURL');
    }

}
