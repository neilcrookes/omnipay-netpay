<?php

namespace Kong\NetPay\Message;

use Guzzle\Http\Exception\BadResponseException;
use Omnipay\Common\CreditCard;

/**
 * NetPay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const OPERATION_MODE_LIVE = 1;
    const OPERATION_MODE_TEST = 2;

    const TRANSACTION_SOURCE_INTERNET = 'INTERNET';
    const TRANSACTION_SOURCE_MOTO = 'MOTO';

    const PAYMENT_SOURCE_TYPE_CARD = 'CARD';
    const PAYMENT_SOURCE_TYPE_TOKEN = 'TOKEN';

    protected $cardTypes = [
        CreditCard::BRAND_VISA => 'VISA',
        CreditCard::BRAND_MASTERCARD => 'MCRD',
        CreditCard::BRAND_MAESTRO => 'MSTO',
        CreditCard::BRAND_AMEX => 'AMEX',
        CreditCard::BRAND_DINERS_CLUB => 'DINE',
    ];

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSslKeyPath()
    {
        return $this->getParameter('sslKeyPath');
    }

    public function setSslKeyPath($value)
    {
        return $this->setParameter('sslKeyPath', $value);
    }

    public function getSslCertificatePath()
    {
        return $this->getParameter('sslCertificatePath');
    }

    public function setSslCertificatePath($value)
    {
        return $this->setParameter('sslCertificatePath', $value);
    }

    public function getSslKeyPassword()
    {
        return $this->getParameter('sslKeyPassword');
    }

    public function setSslKeyPassword($value)
    {
        return $this->setParameter('sslKeyPassword', $value);
    }

    public function setCardReference( $value )
    {
        if ( ! empty( $value ) )
        {
            $this->setPaymentSourceType( self::PAYMENT_SOURCE_TYPE_TOKEN );
        }
        return parent::setCardReference( $value );
    }

    /**
     * @return string
     */
    public function getTransactionSource()
    {
        $transactionSource = $this->getParameter('transactionSource');
        if ( is_null( $transactionSource ) )
        {
            $transactionSource = self::TRANSACTION_SOURCE_INTERNET;
        }
        return $transactionSource;
    }

    /**
     * @param string $transactionSource
     * @throws \InvalidArgumentException
     */
    public function setTransactionSource($transactionSource)
    {
        if ( ! in_array( $transactionSource, [ self::TRANSACTION_SOURCE_INTERNET, self::TRANSACTION_SOURCE_MOTO ] ) )
        {
            throw new \InvalidArgumentException('Invalid transaction source supplied, must be one of " ' . implode('","', [ self::TRANSACTION_SOURCE_INTERNET, self::TRANSACTION_SOURCE_MOTO ] ) . '"');
        }
        $this->setParameter('transactionSource', $transactionSource);
    }

    /**
     * @return string
     */
    public function getPaymentSourceType()
    {
        $paymentSourceType = $this->getParameter('paymentSourceType');
        if ( is_null( $paymentSourceType ) )
        {
            $paymentSourceType = self::PAYMENT_SOURCE_TYPE_CARD;
        }
        return $paymentSourceType;
    }

    /**
     * @param string $paymentSourceType
     * @throws \InvalidArgumentException
     */
    public function setPaymentSourceType($paymentSourceType)
    {
        if ( ! in_array( $paymentSourceType, [ self::PAYMENT_SOURCE_TYPE_CARD, self::PAYMENT_SOURCE_TYPE_TOKEN ] ) )
        {
            throw new \InvalidArgumentException('Invalid payment source type supplied, must be one of " ' . implode('","', [ self::PAYMENT_SOURCE_TYPE_CARD, self::PAYMENT_SOURCE_TYPE_TOKEN ] ) . '"');
        }
        $this->setParameter('paymentSourceType', $paymentSourceType);
    }

    /**
     * @return array
     */
    protected function getMerchantData()
    {
        return [
            'merchant_id' => $this->getMerchantId(),
            'operation_type' => $this->getOperationType(),
            'operation_mode' => $this->getOperationMode(),
        ];
    }

    /**
     * @return array
     */
    protected function getTransactionData()
    {
        return [
            'source' => $this->getTransactionSource(),
        ];
    }

    /**
     * @return array
     */
    protected function getPaymentSourceCardData()
    {
        $paymentSourceData = [
            'type' => self::PAYMENT_SOURCE_TYPE_CARD,
            'card' => [
                'card_type' => $this->formatCardType($this->getCard()->getBrand()),
                'number' => $this->getCard()->getNumber(),
                'expiry_month' => $this->getCard()->getExpiryDate("m"),
                'expiry_year' => $this->getCard()->getExpiryDate("y"),
                'holder' => [
                    'firstname' => $this->getCard()->getFirstName(),
                    'lastname' => $this->getCard()->getLastName(),
                    'fullname' => $this->getCard()->getFirstName() . ' ' . $this->getCard()->getLastName(),
                ]
            ]
        ];

        $title = $this->getCard()->getTitle();
        if ( ! empty( $title ) )
        {
            $paymentSourceData[ 'card' ][ 'holder' ][ 'title' ] = $title;
            $paymentSourceData[ 'card' ][ 'holder' ][ 'fullname' ] = $title . ' ' . $paymentSourceData[ 'card' ][ 'holder' ][ 'fullname' ];
        }

        return $paymentSourceData;
    }

    /**
     * @param string $brand
     * @return string
     */
    protected function formatCardType($brand)
    {
        return $this->cardTypes[ $brand ];
    }

    /**
     * @return array
     */
    protected function getPaymentSourceTokenData()
    {
        $paymentSourceData = [
            'type' => self::PAYMENT_SOURCE_TYPE_TOKEN,
            'token' => $this->getCardReference(),
        ];

        return $paymentSourceData;
    }

    /**
     * @param array $data
     * @return Response
     * @throws BadResponseException
     */
    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' =>  'application/json'
        ];
        $body = json_encode( $data );
        $cert = $this->getSslCertificatePath();
        $sslKeyPath = $this->getSslKeyPath();
        $sslKeyPassword = $this->getSslKeyPassword();
        if ( $cert && $sslKeyPath && $sslKeyPassword )
        {
            $options = [
                'cert' => $cert,
                'ssl_key' => [ $sslKeyPath, $sslKeyPassword ],
            ];
        }
        else
        {
            $options = [];
        }
        $request = $this->httpClient->put( $url, $headers, $body, $options );
        $request->setAuth( $this->getUsername(), $this->getPassword() );
        try
        {
            $httpResponse = $request->send();
        }
        catch ( BadResponseException $e )
        {
            $response = $e->getResponse();
            // If we're in test mode and the bad response is because of an invalid request, provide some better debug info
            if ( $this->getTestMode() && $response->getStatusCode() == 400 )
            {
                // Get the actual response body
                $responseBodyAsString = $response->getBody( true );
                $responseBody = json_decode( $responseBodyAsString );
                if ( $responseBody !== null )
                {
                    // Overwrite the reason phrase for the bad response in the response itself (by default it's "Bad Request" for 400 status code)
                    $response->setStatus( 400, $responseBody->error->explanation . "\n<pre>" . json_encode( $data, JSON_PRETTY_PRINT) . '</pre>');
                }
                else
                {
                    $response->setStatus( 400, $responseBodyAsString);
                }
                // Rebuild the exception, since you can't set the message any other way other than through the constructor
                $e = $e->factory( $e->getRequest(), $response );
            }
            throw $e;
        }
        return $this->createResponse($httpResponse->getBody());
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @return string
     */
    protected function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return string
     */
    protected function getOperationMode()
    {
        return $this->getTestMode() ? self::OPERATION_MODE_TEST : self::OPERATION_MODE_LIVE;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
