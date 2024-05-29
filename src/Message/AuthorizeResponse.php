<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class AuthorizeResponse extends Response
{
    public function isSuccessful(): bool
    {
        info('AuthorizeResponse DATA::::', [$this->data]);
        return parent::isSuccessful()
            && $this->data['XMLOut']['Message']['OrderStatus'] === self::ORDER_STATUS_PREAUTH_APPROVED
            && in_array($this->data['XMLOut']['Message']['ResponseCode'], self::SUCCESS_RESPONSE_CODES);
    }

    public function getTransactionReference(): ?string
    {
        $ref = json_encode([
            'sessionId' => $this->data['XMLOut']['Message']['SessionID'],
            'orderId' => $this->data['XMLOut']['Message']['OrderID'],
        ]);

        if ($ref === false) {
            return null;
        }

        info('TRANSACTION REFERENCE::::', [$ref]);
        info('DATA::::', [$this->data]);
        return $ref;
    }
}
