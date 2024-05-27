<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class PurchaseResponse extends Response
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && $this->data['Response']['out']['XMLOut']['Message']['OrderStatus'] === self::ORDER_STATUS_APPROVED
            && in_array($this->data['Response']['out']['XMLOut']['Message']['ResponseCode'], self::SUCCESS_RESPONSE_CODES);
    }
}
