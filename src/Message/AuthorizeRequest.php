<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;

class AuthorizeRequest extends AbstractRequest
{
    public function getData(): array
    {
        return [
            'Merchant' => $this->getMerchant(),
            'MerchantCertificate' => $this->getMerchantCertificate(),
            'MerchantKey' => $this->getMerchantKey(),
            'Amount' => $this->getAmount(),
            'Currency' => 944, //TODO,
            'ApproveURL' => $this->getApproveUrl(),
            'CancelURL' => $this->getCancelUrl(),
            'DeclineURL' => $this->getDeclineUrl(),
            'Language' => $this->getLanguage(),
            'OrderID' => $this->getOrderId(),
            'SessionID' => $this->getSessionId(),
            'SenderCardUID' => $this->getSenderCardUID(),
            'eci' => $this->getEci(),
        ];
    }

    public function sendData($data)
    {
        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildPurchaseXml()]));
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new AuthorizeResponse($this, $data, $statusCode);
    }
}

