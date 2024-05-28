<?php

namespace Ampeco\OmnipayKapitalbank;

use DOMDocument;
use DOMException;

class XmlBuilder
{
    public const CREATE_ORDER_OPERATION = 'CreateOrder';
    public const ORDER_TYPE_PURCHASE = 'Purchase';
    public const ORDER_TYPE_AUTHORIZE = 'PreAuth';
    public const ORDER_TYPE_REVERSE = 'Reverse';
    public const ORDER_TYPE_COMPLETION = 'Completion';
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
        $currency = $this->domDocument->createElement('Currency', $this->data['Currency']);
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

    /**
     * @throws DOMException
     */
    public function buildInitialXml($orderType = self::ORDER_TYPE_PURCHASE): bool|string
    {
        $tkkpg = $this->domDocument->createElement('TKKPG');
        $request = $this->domDocument->createElement('Request');
        $operation = $this->domDocument->createElement('Operation', self::CREATE_ORDER_OPERATION);
        $language = $this->domDocument->createElement('Language', $this->data['Language']);
        $order = $this->domDocument->createElement('Order');
        $orderType = $this->domDocument->createElement('OrderType', $orderType);
        $merchant = $this->domDocument->createElement('Merchant', $this->data['Merchant']);
        $amount = $this->domDocument->createElement('Amount', $this->data['Amount']);
        $currency = $this->domDocument->createElement('Currency', $this->data['Currency']);
        $description = $this->domDocument->createElement('Description', self::ORDER_TYPE_PURCHASE);
        $approveUrl = $this->domDocument->createElement('ApproveURL', $this->data['ApproveURL']);
        $cancelUrl = $this->domDocument->createElement('CancelURL', $this->data['CancelURL']);
        $declineUrl = $this->domDocument->createElement('DeclineURL', $this->data['DeclineURL']);
        $addParams = $this->domDocument->createElement('AddParams');
        $senderCardUid = $this->domDocument->createElement('SenderCardUID', $this->data['SenderCardUID']);

        $tkkpg->appendChild($request);
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
        $order->appendChild($addParams);
        $addParams->appendChild($senderCardUid);

        $this->domDocument->appendChild($tkkpg);

        return $this->domDocument->saveXML();
    }

    /**
     * @throws DOMException
     */
    public function buildPurchaseXml($operation = self::ORDER_TYPE_PURCHASE): bool|string
    {
        $tkkpg = $this->domDocument->createElement('TKKPG');
        $request = $this->domDocument->createElement('Request');
        $operation = $this->domDocument->createElement('Operation', $operation);
        $language = $this->domDocument->createElement('Language', $this->data['Language']);
        $order = $this->domDocument->createElement('Order');
        $merchant = $this->domDocument->createElement('Merchant', $this->data['Merchant']);
        $orderId = $this->domDocument->createElement('OrderID', $this->data['OrderID']);
        $sessionId = $this->domDocument->createElement('SessionID', $this->data['SessionID']);
        $amount = $this->domDocument->createElement('Amount', $this->data['Amount']);
        $currency = $this->domDocument->createElement('Currency', $this->data['Currency']);
        $cardUid = $this->domDocument->createElement('CardUID', $this->data['SenderCardUID']);
        $eci = $this->domDocument->createElement('eci', $this->data['eci']);

        $tkkpg->appendChild($request);
        $request->appendChild($operation);
        $request->appendChild($language);
        $request->appendChild($order);
        $order->appendChild($merchant);
        $order->appendChild($orderId);
        $request->appendChild($sessionId);
        $request->appendChild($amount);
        $request->appendChild($currency);
        $request->appendChild($cardUid);
        $request->appendChild($eci);
        $tkkpg->appendChild($request);

        $this->domDocument->appendChild($tkkpg);
        info('XML::::', [$this->domDocument->saveXML()]);
        return $this->domDocument->saveXML();
    }

    public function buildVoidXml()
    {
        $tkkpg = $this->domDocument->createElement('TKKPG');
        $request = $this->domDocument->createElement('Request');
        $operation = $this->domDocument->createElement('Operation', self::ORDER_TYPE_REVERSE);
        $language = $this->domDocument->createElement('Language', $this->data['Language']);
        $order = $this->domDocument->createElement('Order');
        $merchant = $this->domDocument->createElement('Merchant', $this->data['Merchant']);
        $orderId = $this->domDocument->createElement('OrderID', $this->data['OrderID']);
        $sessionId = $this->domDocument->createElement('SessionID', $this->data['SessionID']);
        $paymentSubjectType = $this->domDocument->createElement('PaymentSubjectType', $this->data['PaymentSubjectType']);
        $paymentType = $this->domDocument->createElement('PaymentType', $this->data['PaymentType']);
        $paymentMethodType = $this->domDocument->createElement('PaymentMethodType', $this->data['PaymentMethodType']);
        $quantity = $this->domDocument->createElement('Quantity', $this->data['Quantity']);
        $source = $this->domDocument->createElement('Source', $this->data['Source']);
        $positions = $this->domDocument->createElement('Positions');
        $position = $this->domDocument->createElement('Position');
        $description = $this->domDocument->createElement('Description','');

        $tkkpg->appendChild($request);
        $request->appendChild($operation);
        $request->appendChild($language);
        $request->appendChild($order);
        $request->appendChild($sessionId);
        $request->appendChild($source);
        $request->appendChild($description);
        $order->appendChild($positions);
        $order->appendChild($orderId);
        $order->appendChild($merchant);
        $positions->appendChild($position);
        $position->appendChild($paymentMethodType);
        $position->appendChild($paymentSubjectType);
        $position->appendChild($paymentType);
        $position->appendChild($quantity);

        $this->domDocument->appendChild($tkkpg);

        return $this->domDocument->saveXML();

    }

    public function buildCaptureXml()
    {
        $tkkpg = $this->domDocument->createElement('TKKPG');
        $request = $this->domDocument->createElement('Request');
        $operation = $this->domDocument->createElement('Operation', self::ORDER_TYPE_COMPLETION);
        $language = $this->domDocument->createElement('Language', $this->data['Language']);
        $order = $this->domDocument->createElement('Order');
        $merchant = $this->domDocument->createElement('Merchant', $this->data['Merchant']);
        $orderId = $this->domDocument->createElement('OrderID', $this->data['OrderID']);
        $sessionId = $this->domDocument->createElement('SessionID', $this->data['SessionID']);
        $description = $this->domDocument->createElement('Description','');
        $amount = $this->domDocument->createElement('Amount', $this->data['Amount']);
        $tkkpg->appendChild($request);
        $request->appendChild($operation);
        $request->appendChild($language);
        $request->appendChild($sessionId);
        $request->appendChild($amount);
        $request->appendChild($description);
        $request->appendChild($order);
        $order->appendChild($merchant);
        $order->appendChild($orderId);
        $this->domDocument->appendChild($tkkpg);

        return $this->domDocument->saveXML();
    }
}
