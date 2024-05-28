<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;

class InitialPurchaseRequest extends AbstractRequest
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
            'SenderCardUID' => $this->getSenderCardUID(),
        ];
    }

    public function sendData($data)
    {
        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildInitialXml($this->getOrderType())]));
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new InitialResponse($this, $data, $statusCode);
    }
}


