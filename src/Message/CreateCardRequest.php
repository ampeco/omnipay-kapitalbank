<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;
use Omnipay\Common\Message\ResponseInterface;

class CreateCardRequest extends AbstractRequest
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
            'Name' => $this->getName(),
            'Title' => $this->getTitle(),
        ];
    }

    public function sendData($data): ResponseInterface|Response
    {
//        return parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildCreateCardXml()]));
        return parent::sendData(
//            array_merge($data, ['payload' => (new XmlBuilder($data))->buildPurchaseXml()])
            parent::constructDataPayload($data, (new XmlBuilder($data))->buildCreateCardXml())
        );
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new CreateCardResponse($this, $data, $statusCode);
    }
}
