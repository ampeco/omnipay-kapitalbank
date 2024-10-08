<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\NotificationInterface;

class CreateCardNotification implements NotificationInterface
{
    private const RESPONSE_CODE_APPROVED = '000';
    private const RESPONSE_CODE_APPROVED_TOKENIZE = '001';

    public function __construct(protected array $data)
    {
        $xmlResponseData = simplexml_load_string($this->data['xmlmsg']);
        $jsonString = json_encode($xmlResponseData);

        $this->data = json_decode($jsonString, true) ?? null;
    }

    public function getData(): ?string
    {
        return $this->data['Message']['OrderDescription'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        if (!isset($this->data['Message']['SessionID']) || !isset($this->data['Message']['OrderID'])) {
            return null;
        }

        $ref = json_encode([
            'sessionId' => $this->data['Message']['SessionID'],
            'orderId' => $this->data['Message']['OrderID'],
        ]);

        if ($ref === false) {
            return null;
        }

        return $ref;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->data['Message']['OrderStatus'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['Message']['ResponseDescription'] ?? null;
    }

    public function getCardNumber(): ?string
    {
        if (!isset($this->data['Message']['CardRegistrationResponse']['MaskedPAN'])) {
            return null;
        }

        return '**** ' . substr($this->data['Message']['CardRegistrationResponse']['MaskedPAN'], 12, 4);
    }

    public function getCardReference(): string
    {
        return $this->data['Message']['CardRegistrationResponse']['CardUID'];
    }

    public function getMaskedPAN(): ?string
    {
        return $this->data['Message']['CardRegistrationResponse']['MaskedPAN'] ?? null;
    }

    public function getCardType(): ?string
    {
        return $this->data['Message']['CardRegistrationResponse']['Brand'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return
            isset($this->data['Message']['ResponseCode'])
            && in_array($this->data['Message']['ResponseCode'], [self::RESPONSE_CODE_APPROVED_TOKENIZE, self::RESPONSE_CODE_APPROVED])
            && ($this->data['Message']['CardRegistrationResponse']['CardUID'] ?? false);
    }
}
