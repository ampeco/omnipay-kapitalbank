<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class InitialResponse extends Response
{
    public function isSuccessful() : bool
    {
        return parent::isSuccessful()
            && isset($this->data['Response']['Status'])
            && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE
            && isset($this->data['Response']['Order']['SessionID'])
            && isset($this->data['Response']['Order']['OrderID']);
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getSessionId(): string
    {
        return $this->data['Response']['Order']['SessionID'];
    }

    public function getOrderId(): string
    {
        return $this->data['Response']['Order']['OrderID'];
    }
}
