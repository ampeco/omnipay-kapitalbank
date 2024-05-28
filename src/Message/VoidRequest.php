<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;

class VoidRequest extends AbstractRequest
{

    public function getData(): array
    {
        return [
            'Merchant' => $this->getMerchant(),
            'MerchantCertificate' => $this->getMerchantCertificate(),
            'MerchantKey' => $this->getMerchantKey(),
            'OrderID' => $this->getOrderId(),
            'SessionID' => $this->getSessionId(),
            'PaymentSubjectType' => 1,
            'Quantity' => 1,
            'PaymentType' => 2,
            'PaymentMethodType' => 1,
            'Source' => 1,
        ];
    }

    public function sendData($data)
    {
        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildVoidXml()]));
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new VoidResponse($this, $data, $statusCode);
    }
}
