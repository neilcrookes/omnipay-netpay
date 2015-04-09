<?php

namespace Omnipay\NetPay\Message;

class ApiAuthorizeTransactionRequest extends AbstractTransactionRequest
{
    protected $operationType = 'AUTHORIZE';
    
    public function getData()
    {
        $this->validate('amount', 'currency', 'transactionId', 'description');

        if ( $this->getPaymentSourceType() == self::PAYMENT_SOURCE_TYPE_CARD )
        {
            $this->validate( 'card' );
            $this->getCard()->validate();

            $data = [
                'merchant' => $this->getMerchantData(),
                'transaction' => $this->getTransactionData(),
                'payment_source' => $this->getPaymentSourceData(),
                'customer' => $this->getCustomerData(),
            ];

            $billingData = $this->getBillingData();

            if ( ! empty( $billingData ) )
            {
                $data[ 'billing' ] = $billingData;
            }
        }
        else // TOKEN
        {
            $this->validate( 'cardReference' );

            $data = [
                'merchant' => $this->getMerchantData(),
                'transaction' => $this->getTransactionData(),
                'payment_source' => $this->getPaymentSourceData(),
            ];
        }

        return $data;
    }
}
