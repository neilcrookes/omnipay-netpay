<?php

namespace Omnipay\NetPay\Message;
use Guzzle\Http\Exception\BadResponseException;

/**
 * NetPay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    const OPERATION_MODE_LIVE = 1;
    const OPERATION_MODE_TEST = 2;

    const TRANSACTION_SOURCE_INTERNET = 'INTERNET';
    const TRANSACTION_SOURCE_MOTO = 'MOTO';

    protected $operationType;

    protected $transactionSource = self::TRANSACTION_SOURCE_INTERNET;

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

    /**
     * @return string
     */
    public function getTransactionSource()
    {
        return $this->transactionSource;
    }

    /**
     * @param string $transactionSource
     * @throws \Exception
     */
    public function setTransactionSource($transactionSource)
    {
        if ( ! in_array( $transactionSource, [ self::TRANSACTION_SOURCE_INTERNET, self::TRANSACTION_SOURCE_MOTO ] ) )
        {
            throw new \Exception('Invalid transaction source supplied, must be one of " ' . implode('","', [ self::TRANSACTION_SOURCE_INTERNET, self::TRANSACTION_SOURCE_MOTO ] ) . '"');
        }
        $this->transactionSource = $transactionSource;
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

    protected function getBaseData()
    {
        $data = [
            'merchant' => [
                'merchant_id' => $this->getMerchantId(),
                'operation_type' => $this->getOperationType(),
                'operation_mode' => $this->getOperationMode(),
            ]
        ];
        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' =>  'application/json'
        ];
        $body = json_encode( $data );
        $options = [
            'cert' => $this->getSslCertificatePath(),
            'ssl_key' => [ $this->getSslKeyPath(), $this->getSslKeyPassword() ],
        ];
        $request = $this->httpClient->put( $url, $headers, $body, $options );
        $request->setAuth( $this->getUsername(), $this->getPassword() );
        $httpResponse = $request->send();
        return $this->createResponse($httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function getOperationType()
    {
        return $this->operationType;
    }

    protected function getOperationMode()
    {
        return $this->getTestMode() ? self::OPERATION_MODE_TEST : self::OPERATION_MODE_LIVE;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
