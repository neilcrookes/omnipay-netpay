<?php

namespace Kong\NetPay\Message;

/**
 * NetPay Fetch Transaction Request
 */
class ApiVoidTransactionRequest extends AbstractTransactionRequest
{
    protected $operationType = 'VOID';

    public function getData()
    {
        $this->validate('transactionReference');

        $data = [
            'merchant' => $this->getMerchantData(),
            'transaction' => [
                'transaction_id' => $this->getTransactionId(),
                'void_transaction_id' => $this->getTransactionId(), // @todo figure out how to get this piece of data
                'description' => $this->getDescription(),
            ],
            'order' => [
                'order_id' => $this->getTransactionReference(),
            ],
        ];

        return $data;
    }
}
