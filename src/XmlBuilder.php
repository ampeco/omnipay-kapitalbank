<?php

namespace Ampeco\OmnipayKapitalbank;

use DOMDocument;
use DOMException;

class XmlBuilder
{
    private const CREATE_ORDER_OPERATION = 'CreateOrder';
    private const ORDER_TYPE_PURCHASE = 'Purchase';
    private DOMDocument $domDocument;

    public function __construct(private readonly array $data)
    {
        $this->domDocument = new DOMDocument('1.0', 'utf-8');
    }

    /**
     * @throws DOMException
     */
    public function buildCreateCardXml(): bool|string
    {
        $tkkpg = $this->domDocument->createElement('TKKPG');
        $request = $this->domDocument->createElement('Request');
        $operation = $this->domDocument->createElement('Operation', self::CREATE_ORDER_OPERATION);
        $language = $this->domDocument->createElement('Language', $this->data['Language']);
        $order = $this->domDocument->createElement('Order');
        $orderType = $this->domDocument->createElement('OrderType', self::ORDER_TYPE_PURCHASE);
        $merchant = $this->domDocument->createElement('Merchant', $this->data['Merchant']);
        $amount = $this->domDocument->createElement('Amount', $this->data['Amount']);
        $currency = $this->domDocument->createElement('Currency', '944'); // TODO Take into account user currency form data and exchange if needed
        $description = $this->domDocument->createElement('Description', self::CREATE_ORDER_OPERATION);
        $approveUrl = $this->domDocument->createElement('ApproveURL', $this->data['ApproveURL']);
        $cancelUrl = $this->domDocument->createElement('CancelURL', $this->data['CancelURL']);
        $declineUrl = $this->domDocument->createElement('DeclineURL', $this->data['DeclineURL']);
        $cardRegistration = $this->domDocument->createElement('CardRegistration');
        $regOnSuccess = $this->domDocument->createElement('RegisterCardOnSuccess', true);
        $checkRegCard = $this->domDocument->createElement('CheckRegisterCardOn', true);
        $saveUuidOrder = $this->domDocument->createElement('SaveCardUIDToOrder', true);
        $addParams = $this->domDocument->createElement('AddParams');
        $customFields = $this->domDocument->createElement('CustomFields');
        $param = $this->domDocument->createElement('Param');
        $param->setAttribute('name', 'Attention');
        $param->setAttribute('title', 'By clicking Register card I agree to save the token of my bank card for further convenience of payments.'); // TODO TRANSLATE ACCORDING TO LANG

        $request->appendChild($operation);
        $request->appendChild($language);
        $request->appendChild($order);
        $order->appendChild($orderType);
        $order->appendChild($merchant);
        $order->appendChild($amount);
        $order->appendChild($currency);
        $order->appendChild($description);
        $order->appendChild($approveUrl);
        $order->appendChild($cancelUrl);
        $order->appendChild($declineUrl);
        $order->appendChild($cardRegistration);
        $cardRegistration->appendChild($regOnSuccess);
        $cardRegistration->appendChild($checkRegCard);
        $cardRegistration->appendChild($saveUuidOrder);
        $order->appendChild($addParams);
        $addParams->appendChild($customFields);
        $customFields->appendChild($param);
        $tkkpg->appendChild($request);

        $this->domDocument->appendChild($tkkpg);

        return $this->domDocument->saveXML();
    }
}
