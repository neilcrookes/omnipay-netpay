<?php

namespace Omnipay\NetPay\Message;

/**
 * NetPay Purchase Request
 */
class ApiPurchaseTransactionRequest extends ApiAuthorizeTransactionRequest
{
    protected $operationType = 'PURCHASE';
}
