<?php

namespace Kong\NetPay\Message;

/**
 * NetPay Retrieve Request
 */
class ApiRetrieveTransactionRequest extends AbstractTransactionRequest
{
    protected $operationType = 'RETRIEVE';

    public function getData()
    {
        $this->validate('transactionReference');

        $data = [
            'merchant' => $this->getMerchantData(),
            'order' => [
                'order_id' => $this->getTransactionReference(),
            ],
        ];

        return $data;
    }
}
