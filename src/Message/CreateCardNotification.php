<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\NotificationInterface;

class CreateCardNotification implements NotificationInterface
{
    private const ORDER_STATUS_CREATED = 'CREATED';
    private const ORDER_STATUS_ONLOCK = 'ON-LOCK';
    private const ORDER_STATUS_CREATED_ONLOCK = 'CREATEDON-LOCK';
    private const ORDER_STATUS_APPROVED = 'APPROVED';
    private const ORDER_STATUS_CANCELED = 'CANCELED';
    private const ORDER_STATUS_DECLINED = 'DECLINED';

    private string $transactionStatus;
    private ?string $transactionReference;
    private ?array $message;

    public function __construct(protected array $data) {
        $xmlResponseData = simplexml_load_string($this->data['xmlmsg']);
        $jsonString = json_encode($xmlResponseData);
        $this->data = json_decode($jsonString, true) ?? null;

        $this->setTransactionReference();
        $this->setMessage();
        $this->setTransactionStatus();
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference(): ?string
    {
        return $this->transactionReference;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionStatus(): string
    {
        return $this->transactionStatus;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?array
    {
        return $this->message;
    }

    private function setTransactionReference(): void
    {
        $this->transactionReference = $this->data['Message']['TranId'] ?? null;
    }

    private function setTransactionStatus(): void
    {
        $status = $this->data['Message']['OrderStatus'] ?? null;

        if (!$status) {
            $this->transactionStatus = self::STATUS_FAILED;
            return;
        }

        if ($status === self::ORDER_STATUS_APPROVED) {
            $this->transactionStatus = self::STATUS_COMPLETED;
            return;
        }

        if (in_array(
            $status,
            [self::ORDER_STATUS_CREATED, self::ORDER_STATUS_ONLOCK, self::ORDER_STATUS_CREATED_ONLOCK]
        )) {
            $this->transactionStatus = self::STATUS_PENDING;
        } else {
            $this->transactionStatus = self::STATUS_FAILED;
        }
    }

    private function setMessage(): void
    {
        $this->message = $this->data['Message'] ?? null;
    }
}
