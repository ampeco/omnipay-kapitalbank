<?php

namespace Ampeco\OmnipayKapitalbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{

    const ADD_CARD_STATUS_CODE = '00';

    public function __construct(RequestInterface $request, array $data, protected int $code)
    {
        parent::__construct($request, $data);
    }

    public function isSuccessful(): bool
    {
        return $this->code == 0; // TODO check
    }
}
