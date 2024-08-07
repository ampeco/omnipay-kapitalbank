<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class PurchaseResponse extends Response
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && isset($this->data['XMLOut']['Message']['OrderStatus'])
            && isset($this->data['XMLOut']['Message']['ResponseCode'])
            && $this->data['XMLOut']['Message']['OrderStatus'] === self::ORDER_STATUS_APPROVED
            && in_array($this->data['XMLOut']['Message']['ResponseCode'], self::SUCCESS_RESPONSE_CODES);
    }
}
