<?php

namespace Omnipay\NetPay\Message;

class ApiCreateTokenRequest extends AbstractTokenRequest
{
    protected $operationType = 'CREATE_TOKEN';

    public function getData()
    {
        $this->validate('card');
        $this->getCard()->validate();

        $data = [
            'merchant' => $this->getMerchantData(),
            'transaction' => $this->getTransactionData(),
            'payment_source' => $this->getPaymentSourceCardData(),
            'token_mode' => $this->getTokenMode(),
        ];

        return $data;
    }
}
