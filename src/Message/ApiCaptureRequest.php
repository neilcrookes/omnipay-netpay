<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Capture Request
 */
class ApiCaptureRequest extends AbstractTransactionRequest
{
    protected $operationType = 'CAPTURE';

    public function getData()
    {
        $this->validate('transactionReference', 'amount', 'currency');

        $data = $this->getBaseData();
        $data['transaction'] = $this->getTransactionData();
        $data['order']['order_id'] = $this->getTransactionReference();

        return $data;
    }
}
