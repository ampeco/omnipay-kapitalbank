<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class InitialResponse extends Response
{
    public function isSuccessful() : bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getSessionId(): ?string
    {
        return @$this->data['Response']['Order']['SessionID'];
    }

    public function getOrderId(): ?string
    {
        return @$this->data['Response']['Order']['OrderID'];
    }
}
