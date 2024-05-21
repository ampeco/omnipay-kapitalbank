<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\NotificationInterface;

class CreateCardNotification implements NotificationInterface
{
    public function __construct(protected array $data) {}

    public function getData(): array
    {
        $xmlResponseData = simplexml_load_string($this->data['xmlmsg']);
        $jsonString = json_encode($xmlResponseData);
        $this->data = json_decode($jsonString, true) ?? null;
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        // TODO: Implement getTransactionReference() method.
    }

    /**
     * @inheritDoc
     */
    public function getTransactionStatus()
    {
        // TODO: Implement getTransactionStatus() method.
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }
}
