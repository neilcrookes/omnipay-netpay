<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Fetch Transaction Request
 */
class ApiVoidRequest extends AbstractTransactionRequest
{
    protected $operationType = 'VOID';

    public function getData()
    {
        $this->validate('transactionReference');

        $data = $this->getBaseData();
        $data['order']['order_id'] = $this->getTransactionReference();

        return $data;
    }
}
