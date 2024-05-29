<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;
use Omnipay\Common\Message\ResponseInterface;

class InitialRequest extends AbstractRequest
{

    public function getData(): array
    {
        return [
            'Merchant' => $this->getMerchant(),
            'MerchantCertificate' => $this->getMerchantCertificate(),
            'MerchantKey' => $this->getMerchantKey(),
            'Amount' => $this->getAmount(),
            'Currency' => $this->getCurrencyNumeric(),
            'ApproveURL' => $this->getApproveUrl(),
            'CancelURL' => $this->getCancelUrl(),
            'DeclineURL' => $this->getDeclineUrl(),
            'Language' => $this->getLanguage(),
            'SenderCardUID' => $this->getSenderCardUID(),
        ];
    }

    public function sendData($data): ResponseInterface|Response
    {
        return parent::sendData(
            parent::constructDataPayload($data, (new XmlBuilder($data))->buildInitialXml($this->getOrderType()))
        );
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new InitialResponse($this, $data, $statusCode);
    }
}


