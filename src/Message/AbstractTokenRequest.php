<?php

namespace Kong\NetPay\Message;

/**
 * NetPay Abstract Transaction Request
 */
abstract class AbstractTokenRequest extends AbstractRequest
{
    const TOKEN_MODE_PERMANENT = 'PERMANENT';
    const TOKEN_MODE_TEMPORARY = 'TEMPORARY';

    protected $testEndpoint = 'https://integrationtest.revolution.netpay.co.uk/v1/gateway/token';
    protected $liveEndpoint = 'https://integration.revolution.netpay.co.uk/v1/gateway/token';

    protected function getTokenMode()
    {
        $tokenMode = $this->getParameter('tokenMode');
        if ( is_null( $tokenMode ) )
        {
            return self::TOKEN_MODE_PERMANENT;
        }
        return $tokenMode;
    }

    /**
     * @param string $tokenMode
     * @throws \Exception
     */
    public function setTokenMode($tokenMode)
    {
        if ( ! in_array( $tokenMode, [ self::TOKEN_MODE_PERMANENT, self::TOKEN_MODE_TEMPORARY ] ) )
        {
            throw new \Exception('Invalid token mode supplied, must be one of " ' . implode('","', [ self::TOKEN_MODE_PERMANENT, self::TOKEN_MODE_TEMPORARY ] ) . '"');
        }
        $this->setParameter('tokenMode', $tokenMode);
    }
}
