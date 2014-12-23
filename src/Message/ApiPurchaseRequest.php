<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Purchase Request
 */
class ApiPurchaseRequest extends ApiAuthorizeRequest
{
    protected $operationType = 'PURCHASE';
}
