<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class VoidResponse extends Response
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->data['Response']['Status'] == self::SUCCESS_STATUS_CODE
            && in_array($this->data['Response']['Reversal']['RespCode'], self::SUCCESS_RESPONSE_CODES);
    }
}
