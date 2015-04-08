<?php

namespace Omnipay\NetPay\Message;

class ApiAuthorizeTransactionRequest extends AbstractTransactionRequest
{
    protected $operationType = 'AUTHORIZE';
    
    public function getData()
    {
        $this->validate('amount', 'card', 'currency', 'transactionId', 'description');

        $this->getCard()->validate();

        $data = [
            'merchant' => $this->getMerchantData(),
            'transaction' => $this->getTransactionData(),
            'payment_source' => $this->getPaymentSourceData(),
            'billing' => $this->getBillingData(),
            'customer' => $this->getCustomerData(),
        ];

        return $data;
    }
}
