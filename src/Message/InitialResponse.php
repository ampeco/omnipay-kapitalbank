<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class InitialResponse extends Response
{
    public function isSuccessful() : bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE; // TODO TO PARENT ?
    }

    public function getData()
    {
        return $this->data;
    }

    public function getSessionId()
    {
        return @$this->data['Response']['Order']['SessionID'];
    }

    public function getOrderId()
    {
        return @$this->data['Response']['Order']['OrderID'];
    }

}
