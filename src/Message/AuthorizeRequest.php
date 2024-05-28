<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;

class AuthorizeRequest extends PurchaseRequest
{
    public function sendData($data)
    {
        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildPurchaseXml(XmlBuilder::ORDER_TYPE_AUTHORIZE)]));
    }
}
