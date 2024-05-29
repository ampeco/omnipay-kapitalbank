<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class CreateCardResponse extends Response implements RedirectResponseInterface
{
    public function isSuccessful() : bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE;
    }

    public function getOrder(): ?array
    {
        return $this->data['Response']['Order'];
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl(): string
    {
        return sprintf(
            "%s?ORDERID=%s&SESSIONID=%s",
            $this->getUrl(),
            $this->getOrderId(),
            $this->getSessionId()
        );
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    private function getUrl(): ?string
    {
        return $this->getOrder()['URL'] ?? null;
    }

    private function getOrderId(): ?string
    {
        return $this->getOrder()['OrderID'] ?? null;
    }

    private function getSessionId(): ?string
    {
        return $this->getOrder()['SessionID'] ?? null;
    }
}
