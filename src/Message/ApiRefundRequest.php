<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Refund Request
 */
class ApiRefundRequest extends AbstractTransactionRequest
{
    protected $operationType = 'REFUND';

    public function getData()
    {
        $this->validate('transactionReference', 'amount', 'currency');

        $data = $this->getBaseData();
        $data['transaction'] = $this->getTransactionData();
        $data['order']['order_id'] = $this->getTransactionReference();

        return $data;
    }
}
