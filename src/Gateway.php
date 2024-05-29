<?php

namespace Ampeco\OmnipayKapitalbank;

use Ampeco\OmnipayKapitalbank\Message\AuthorizeRequest;
use Ampeco\OmnipayKapitalbank\Message\CaptureRequest;
use Ampeco\OmnipayKapitalbank\Message\CreateCardNotification;
use Ampeco\OmnipayKapitalbank\Message\CreateCardRequest;
use Ampeco\OmnipayKapitalbank\Message\InitialRequest;
use Ampeco\OmnipayKapitalbank\Message\PurchaseRequest;
use Ampeco\OmnipayKapitalbank\Message\VoidRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Kapital Bank';
    }

    public function createCard(array $options = array()): RequestInterface
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    public function initial(array $options = array()): RequestInterface
    {
        return $this->createRequest(InitialRequest::class, $options);
    }

    public function purchase(array $options = array()): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function getCreateCardCurrency(): string
    {
        return 'AZN';
    }

    public function getCreateCardAmount(): float
    {
        return 1;
    }

    public function acceptNotification(array $options = array()): CreateCardNotification
    {
        return new CreateCardNotification($options);
    }

    public function authorize(array $options = array()): RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function void(array $options = array()): RequestInterface
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    public function capture(array $options = array()): RequestInterface
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }
}
