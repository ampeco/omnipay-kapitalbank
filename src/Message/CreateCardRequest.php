<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Ampeco\OmnipayKapitalbank\XmlBuilder;

class CreateCardRequest extends AbstractRequest
{

    public function getData(): array
    {
        return [
            'Merchant' => $this->getMerchant(),
            'MerchantCertificate' => $this->getMerchantCertificate(),
            'MerchantKey' => $this->getMerchantKey(),
            'Amount' => $this->getAmount(),
            'ApproveURL' => $this->getApproveUrl(),
            'CancelURL' => $this->getCancelUrl(),
            'DeclineURL' => $this->getDeclineUrl(),
            'Language' => $this->getLanguage(),
        ];
    }

    public function sendData($data)
    {
        parent::sendData(array_merge($data, ['payload' => (new XmlBuilder($data))->buildCreateCardXml()]));
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return $this->response = new CreateCardResponse($this, $data, $statusCode);
    }
}
