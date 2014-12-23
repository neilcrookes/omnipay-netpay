<?php

namespace Omnipay\NetPay\Message;

use Omnipay\Common\CreditCard;

class ApiAuthorizeRequest extends AbstractTransactionRequest
{
    protected $operationType = 'AUTHORIZE';

    protected $cardTypes = [
        CreditCard::BRAND_VISA => 'VISA',
        CreditCard::BRAND_MASTERCARD => 'MCRD',
        CreditCard::BRAND_MAESTRO => 'MSTO',
        CreditCard::BRAND_AMEX => 'AMEX',
        CreditCard::BRAND_DINERS_CLUB => 'DINE',
    ];
    
    public function getData()
    {
        $this->validate('amount', 'card', 'currency');
        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['transaction'] = $this->getTransactionData();
        $data['payment_source'] = $this->getPaymentSourceData();
        $data['billing'] = $this->getBillingData();
        $data['customer'] = $this->getCustomerData();
        return $data;
    }
}
