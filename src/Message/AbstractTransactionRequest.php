<?php

namespace Kong\NetPay\Message;

/**
 * NetPay Abstract Transaction Request
 */
abstract class AbstractTransactionRequest extends AbstractRequest
{
    protected $testEndpoint = 'https://integrationtest.revolution.netpay.co.uk/v1/gateway/transaction';
    protected $liveEndpoint = 'https://integration.revolution.netpay.co.uk/v1/gateway/transaction';

    /**
     * @return array
     */
    protected function getTransactionData()
    {
        $data = parent::getTransactionData();

        $extra = [
            'transaction_id' => $this->getTransactionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
        ];

        return array_merge( $data, $extra );
    }

    /**
     * @return array
     */
    protected function getPaymentSourceData()
    {
        if ( $this->getPaymentSourceType() == self::PAYMENT_SOURCE_TYPE_CARD )
        {
            $paymentSourceData = $this->getPaymentSourceCardData();
        }
        else // TOKEN
        {
            $paymentSourceData = $this->getPaymentSourceTokenData();
        }

        if ( $this->getTransactionSource() == self::TRANSACTION_SOURCE_INTERNET )
        {
            $paymentSourceData['card']['security_code'] = $this->getCard()->getCvv();
        }

        return $paymentSourceData;
    }

    /**
     * @return array
     */
    protected function getBillingData()
    {
        $bill_to_company = $this->getCard()->getCompany();
        if ( ! empty( $bill_to_company ) )
        {
            $billingData[ 'bill_to_company' ] = $bill_to_company;
        }

        $billingData[ 'bill_to_address' ] = $this->getCard()->getAddress1();
        $billingData[ 'bill_to_town_city' ] = $this->getCard()->getCity();

        $bill_to_county = $this->getCard()->getState();
        if ( ! empty( $bill_to_county ) )
        {
            $billingData[ 'bill_to_county' ] = $bill_to_county;
        }

        $bill_to_postcode = $this->getCard()->getPostcode();
        if ( ! empty( $bill_to_postcode ) )
        {
            $billingData[ 'bill_to_postcode' ] = $bill_to_postcode;
        }

        $billingData[ 'bill_to_country' ] = $this->getCard()->getCountry();

        $billingData = array_filter( $billingData );

        return $billingData;
    }

    /**
     * @return array
     */
    protected function getCustomerData()
    {
        $ip = $this->getClientIp();
        // Sometimes more than 1 IP address is returned, so just use the first, otherwise gethostbyaddr() throws an
        // error despite what the PHP manual says.
        if ( strstr( $ip, ',') )
        {
            $ips = explode( ',', $ip );
            $host = gethostbyaddr( trim( $ips[ 0 ] ) );
        }
        elseif ( ! filter_var( $ip, FILTER_VALIDATE_IP) )
        {
            $host = $ip;
        }
        else
        {
            $host = gethostbyaddr( $ip );
        }
        $customerData = [
            'customer_ip_address' => $ip,
            'customer_email' => $this->getCard()->getEmail(),
            'customer_phone' => $this->getCard()->getPhone(),
            'customer_fax' => $this->getCard()->getFax(),
            'customer_hostname' => substr( $host, 0, 60 ),
            // This doesn't seem to work
//            'customer_browser' => substr($_SERVER['HTTP_USER_AGENT'], 0, 60),
        ];

        return array_filter( $customerData );
    }
}
