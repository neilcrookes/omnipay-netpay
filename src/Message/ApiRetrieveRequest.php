<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Retrieve Request
 */
class ApiRetrieveRequest extends AbstractTransactionRequest
{
    protected $operationType = 'RETRIEVE';

    public function getData()
    {
        $this->validate('transactionReference');

        $data = $this->getBaseData();
        $data['order']['order_id'] = $this->getTransactionReference();

        return $data;
    }
}
