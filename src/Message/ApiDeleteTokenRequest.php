<?php

namespace Omnipay\NetPay\Message;

class ApiDeleteTokenRequest extends AbstractTokenRequest
{
    protected $operationType = 'DELETE_TOKEN';

    protected $paymentSourceType = self::PAYMENT_SOURCE_TYPE_TOKEN;

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('cardReference');

        $data = [
            'merchant' => $this->getMerchantData(),
            'transaction' => $this->getTransactionData(),
            'payment_source' => $this->getPaymentSourceData(),
        ];

        return $data;
    }

    /**
     * @return array
     */
    protected function getPaymentSourceData()
    {
        $paymentSourceData = [
            'type' => $this->getPaymentSourceType(),
            'token' => $this->getCardReference(),
        ];

        return $paymentSourceData;
    }
}
