<?php

namespace Kong\NetPay\Message;

/**
 * NetPay Capture Request
 */
class ApiCaptureTransactionRequest extends AbstractTransactionRequest
{
    protected $operationType = 'CAPTURE';

    public function getData()
    {
        $this->validate('amount', 'currency', 'transactionId', 'description', 'transactionReference');

        $data = [
            'merchant' => $this->getMerchantData(),
            'transaction' => $this->getTransactionData(),
            'order' => [
                'order_id' => $this->getTransactionReference(),
            ],
        ];

        return $data;
    }
}
