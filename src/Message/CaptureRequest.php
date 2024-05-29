<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;
use Omnipay\Common\Message\ResponseInterface;

class CaptureRequest extends AbstractRequest
{
    public function getData(): array
    {
        return [
            'Merchant' => $this->getMerchant(),
            'MerchantCertificate' => $this->getMerchantCertificate(),
            'MerchantKey' => $this->getMerchantKey(),
            'Amount' => $this->getAmount(),
            'Language' => $this->getLanguage(),
            'OrderID' => $this->getOrderId(),
            'SessionID' => $this->getSessionId(),
        ];
    }

    public function sendData($data): ResponseInterface|Response
    {
//        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildCaptureXml()]));
        return parent::sendData(
//            array_merge($data, ['payload' => (new XmlBuilder($data))->buildPurchaseXml()])
            parent::constructDataPayload($data, (new XmlBuilder($data))->buildCaptureXml())
        );
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new CaptureResponse($this, $data, $statusCode);
    }
}
