<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class CreateCardResponse extends Response
{

    public function isSuccessful() : bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::ADD_CARD_STATUS_CODE;
    }

    public function getOrder()
    {
        return $this->data['Response']['Order'];
    }
}
