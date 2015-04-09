<?php

namespace Kong\NetPay;

use Omnipay\Common\AbstractGateway;

/**
 * NetPay Api Gateway Class
 */
class ApiGateway extends AbstractGateway
{
    public function getName()
    {
        return 'NetPay API';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'merchantId' => '',
            'sslCertificatePath' => '',
            'sslKeyPath' => '',
            'sslKeyPassword' => '',
            'testMode' => false,
        );
    }

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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiAuthorizeTransactionRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiPurchaseTransactionRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiCaptureTransactionRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiRefundTransactionRequest', $parameters);
    }

    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiRetrieveTransactionRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiVoidTransactionRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiCreateTokenRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Kong\NetPay\Message\ApiDeleteTokenRequest', $parameters);
    }
}
