<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Abstract Transaction Request
 */
abstract class AbstractTokenRequest extends AbstractRequest
{
    const TOKEN_MODE_PERMANENT = 'PERMANENT';
    const TOKEN_MODE_TEMPORARY = 'TEMPORARY';

    protected $testEndpoint = 'https://integrationtest.revolution.netpay.co.uk/v1/gateway/token';
    protected $liveEndpoint = 'https://integration.revolution.netpay.co.uk/v1/gateway/token';

    protected $tokenMode = self::TOKEN_MODE_PERMANENT;

    protected function getTokenMode()
    {
        return $this->tokenMode;
    }
}
