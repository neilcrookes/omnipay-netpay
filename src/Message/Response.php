<?php namespace Omnipay\NetPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * NetPay Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = json_decode($data);
    }

    public function isSuccessful()
    {
        return isset($this->data->result) && in_array($this->data->result, array('SUCCESS'));
    }

    public function getTransactionReference()
    {
        if ( isset( $this->data->order ) &&  isset( $this->data->order->order_id ))
        {
            return $this->data->order->order_id;
        }
        if ( isset( $this->data->token ) )
        {
            return $this->data->token;
        }
    }

    public function getMessage()
    {
        if ( isset( $this->data->response ) &&  isset( $this->data->response->acquirer_message ))
        {
            return $this->data->response->acquirer_message;
        }
        if ( isset( $this->data->error ) &&  isset( $this->data->error->explanation ) )
        {
            return $this->data->error->explanation;
        }
    }
}
