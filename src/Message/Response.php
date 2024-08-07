<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    const SUCCESS_STATUS_CODE = '00';
    const ORDER_STATUS_APPROVED = 'APPROVED';
    const ORDER_STATUS_PREAUTH_APPROVED = 'PREAUTH-APPROVED';
    const SUCCESS_RESPONSE_CODES = ['000', '001'];

    public function __construct(RequestInterface $request, array $data, protected int $code)
    {
        info('KapitalBank Raw Response', ['response' => $data]);
        parent::__construct($request, $data);
    }

    public function isSuccessful(): bool
    {
        return $this->code == 0;
    }
}
