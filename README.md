# Laravel GG Wallet
For Laravel 5.0 and above

## Introduction
Integrate GG wallet in your laravel application easily with this package.

## Getting Started
To get started add the following package to your `composer.json` file using this command.

    composer require bigbharatjain/laravel-gg-wallet

## Configuring
When composer installs Laravel GG Wallet library successfully, register the `Bharat\LaravelGGWallet\GGWalletServiceProvider` in your `config/app.php` configuration file.

```php
'providers' => [
    // Other service providers...
    Bharat\LaravelGGWallet\GGWalletServiceProvider::class,
],
```
Also, add the `GGWallet` facade to the `aliases` array in your `app` configuration file:

```php
'aliases' => [
    // Other aliases
    'GGWallet' => Bharat\LaravelGGWallet\Facades\GGWallet::class,
],
```
#### One more step to go....
On your `config/services.php` add the following configuration

```php
'gg-wallet' => [
    'env' => 'production', // values : (local | production)
    'merchant_id' => 'YOUR_MERCHANT_ID',
    'merchant_key' => 'YOUR_MERCHANT_KEY'
],
```

## Usage

```php
<?php

namespace App\Http\Controllers;

use GGWallet;

class OrderController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function order()
    {
        $payment = GGWallet::with('receive');
        $payment->prepare([
          'order' => $order->id,
          'user' => $user->id,
          'mobile_number' => $user->phonenumber,
          'email' => $user->email,
          'amount' => $order->amount,
          'callback_url' => 'http://example.com/payment/status'
        ]);
        return $payment->receive();
    }

    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paymentCallback()
    {
        $transaction = GGWallet::with('receive');
        
        $response = $transaction->response(); // To get raw response as object
        //Check out response parameters sent by GG here
        
        if($transaction->isSuccessful()){
          //Transaction Successful
        }else if($transaction->isFailed()){
          //Transaction Failed
        }else if($transaction->isOpen()){
          //Transaction Open/Processing
        }
        
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id
        $transaction->getTransactionId(); // Get transaction id
    }    
}
```

Make sure the `callback_url` you have mentioned while receiving payment is `post` on your `routes.php` file, Example see below:

```php
Route::post('/payment/status', 'OrderController@paymentCallback');
```
Important: The `callback_url` must not be csrf protected [Check out here to how to do that](https://laracasts.com/discuss/channels/general-discussion/l5-disable-csrf-middleware-on-certain-routes)
### Get transaction information using order id

```php
<?php

namespace App\Http\Controllers;

use GGWallet;

class OrderController extends Controller
{
    /**
    * Obtain the transaction status/information.
    *
    * @return Object
    */
    public function statusCheck(){
        $status = GGWallet::with('status');
        $status->prepare(['order' => $order->id]);
        $status->check();
        
        $response = $status->response(); // To get raw response as object
        //Check out response parameters sent by GG
        
        if($status->isSuccessful()){
          //Transaction Successful
        }else if($status->isFailed()){
          //Transaction Failed
        }else if($status->isOpen()){
          //Transaction Open/Processing
        }
        
        //get important parameters via public methods
        $status->getOrderId(); // Get order id
        $status->getTransactionId(); // Get transaction id
    }
}
```
