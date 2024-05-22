<?php

namespace Ampeco\OmnipayKapitalbank\Message;

class PurchaseResponse extends Response
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful();
    }
}
