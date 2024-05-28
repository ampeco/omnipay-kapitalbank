<?php

namespace Ampeco\OmnipayKapitalbank;

use Ampeco\OmnipayKapitalbank\Message\AuthorizeRequest;
use Ampeco\OmnipayKapitalbank\Message\CreateCardNotification;
use Ampeco\OmnipayKapitalbank\Message\CreateCardRequest;
use Ampeco\OmnipayKapitalbank\Message\InitialPurchaseRequest;
use Ampeco\OmnipayKapitalbank\Message\NotificationRequest;
use Ampeco\OmnipayKapitalbank\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'Kapital Bank';
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }

    public function createCard(array $options = array())
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    public function initial(array $options = array())
    {
        return $this->createRequest(InitialPurchaseRequest::class, $options);
    }

    public function purchase(array $options = array())
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function getCreateCardCurrency(): string
    {
//        return 'AZN';// TODO
        return 'EUR';
    }

    public function getCreateCardAmount(): float
    {
        return 1;
    }

    public function acceptNotification(array $options = array()): CreateCardNotification
    {
        return new CreateCardNotification($options);
    }

    public function authorize(array $options = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}
