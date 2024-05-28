<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class CaptureResponse extends Response
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE
            && in_array($this->data['Response']['POSResponse']['l']['@attributes']['value'], self::SUCCESS_RESPONSE_CODES);
    }
}
