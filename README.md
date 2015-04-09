# Omnipay: NetPay

**[NetPay](https://www.netpay.co.uk/) driver for the Omnipay PHP payment processing library**

<!--[![Build Status](https://travis-ci.org/kong/omnipay-netpay.png?branch=master)](https://travis-ci.org/kong/omnipay-netpay)-->
<!--[![Latest Stable Version](https://poser.pugx.org/kong/omnipay-netpay/version.png)](https://packagist.org/packages/kong/omnipay-netpay)-->
<!--[![Total Downloads](https://poser.pugx.org/kong/omnipay-netpay/d/total.png)](https://packagist.org/packages/kong/omnipay-netpay)-->

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements [NetPay](https://www.netpay.co.uk/) support for Omnipay.

## Installation

The Omnipay NetPay is installed via [Composer](http://getcomposer.org/). To install, simply run:

`$ composer require kong/omnipay-netpay`

## Basic Usage

The following gateways are provided by this package:

* Api (\Kong\NetPay\ApiGateway)

    ```php
    use Omnipay\Omnipay;
    
    $gateway = Omnipay::create('\\Kong\\NetPay\\ApiGateway');
    $gateway->initialise(
        'username' => 'myusername',
        'password' => 'mypassword',
        'merchantId' => 'mymerchantid',
        'sslCertificatePath' => '/absolute/path/to/certificatefile',
        'sslKeyPath' => '/absolute/path/to/privatekeyfile',
        'sslKeyPassword' => 'myprivatekeypassword',
        'testMode' => false,
    );
    $formData = ['number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2016', 'cvv' => '123'];
    $response = $gateway->purchase(['amount' => '10.00', 'currency' => 'USD', 'card' => $formData])->send();
    
    if ($response->isSuccessful()) {
        // payment was successful: update database
        print_r($response);
    } else {
        // payment failed: display message to customer
        echo $response->getMessage();
    }
    ```

The Hosted Payment Form Integration is not currently supported.

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Gateway Methods

* authorize($options) - authorize an amount on the customer's card
* capture($options) - capture an amount you have previously authorized
* purchase($options) - authorize and immediately capture an amount on the customer's card
* refund($options) - refund an already processed transaction
* void($options) - not working yet
* createCard($options) - returns a response object which includes a cardReference, which can be used for future transactions
* deleteCard($options) - remove a stored card

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/kong/omnipay-netpay/issues),
or better yet, fork the library and submit a pull request.
