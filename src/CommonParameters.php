<?php

namespace Ampeco\OmnipayKapitalbank;

trait CommonParameters
{
    public function setMerchant($value): void
    {
        $this->setParameter('Merchant', $value);
    }

    public function getMerchant(): string
    {
        return $this->getParameter('Merchant');
    }

    public function getAmount(): string
    {
        return number_format(parent::getAmount(), 2, '', '');
    }

    public function setApproveUrl($value): void
    {
        $this->setParameter('ApproveURL', $value);
    }

    public function getApproveUrl(): string
    {
        return $this->getParameter('ApproveURL');
    }

    public function setCancelUrl($value): void
    {
        $this->setParameter('CancelURL', $value);
    }

    public function getCancelUrl(): string
    {
        return $this->getParameter('CancelURL');
    }

    public function setDeclineUrl($value): void
    {
        $this->setParameter('DeclineURL', $value);
    }

    public function getDeclineUrl(): string
    {
        return $this->getParameter('DeclineURL');
    }

    public function setMerchantCertificate($value): void
    {
        $this->setParameter('MerchantCertificate', $value);
    }

    public function getMerchantCertificate(): string
    {
        return $this->getParameter('MerchantCertificate');
    }

    public function setMerchantKey($value): void
    {
        $this->setParameter('MerchantKey', $value);
    }

    public function getMerchantKey(): string
    {
        return $this->getParameter('MerchantKey');
    }

    public function getLanguage(): string
    {
        return strtoupper($this->getParameter('Language'));
    }

    public function setLanguage($value): void
    {
        $this->setParameter('Language', $value);
    }

    public function getSenderCardUID(): string
    {
        return $this->getParameter('SenderCardUID');
    }

    public function setSenderCardUID($value): void
    {
        $this->setParameter('SenderCardUID', $value);
    }

    public function setSessionId($value): void
    {
        $this->setParameter('SessionId', $value);
    }

    public function getSessionId(): string
    {
        return $this->getParameter('SessionId');
    }

    public function setOrderId($value): void
    {
        $this->setParameter('OrderId', $value);
    }

    public function getOrderId(): string
    {
        return $this->getParameter('OrderId');
    }

    public function setEci($value): void
    {
        $this->setParameter('eci', $value);
    }

    public function getEci(): int
    {
        return $this->getParameter('eci');
    }

    public function setOrderType($value): void
    {
        $this->setParameter('OrderType', $value);
    }

    public function getOrderType(): string
    {
        return $this->getParameter('OrderType');
    }

    public function setName($value): void
    {
        $this->setParameter('Name', $value);
    }

    public function getName(): string
    {
        return $this->getParameter('Name');
    }

    public function setTitle($value): void
    {
        $this->setParameter('Title', $value);
    }

    public function getTitle(): string
    {
        return $this->getParameter('Title');
    }
}
