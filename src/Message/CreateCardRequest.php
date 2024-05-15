<?php

namespace Ampeco\OmnipayKapitalBank\Message;

class CreateCardRequest extends AbstractRequest
{

    public function getData(): array
    {
        return [
            'Merchant' => ,
            'Amount' => ,
            'ApproveURL' =>,
            'CancelURL' =>,
            'DeclineURL' =>,
        ];
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        // TODO: Implement createResponse() method.
    }
}
