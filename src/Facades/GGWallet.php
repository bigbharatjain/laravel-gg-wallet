<?php

namespace Bharat\LaravelGGWallet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laravel\Socialite\SocialiteManager
 */
class GGWallet extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    const STATUS_SUCCESSFUL = 'SUCCESS';
    const STATUS_FAILURE = 'FAIL';
    const STATUS_OPEN = 'OPEN';
    const STATUS_CANCEL = 'CANCELED';

    const RESPONSE_SUCCESSFUL="01";
    const RESPONSE_CANCELLED = "141";
    const RESPONSE_FAILED = "227";
    const RESPONSE_PAGE_CLOSED = "810";
    const RESPONSE_CANCELLED_CUSTOMER = "8102";
    const RESPONSE_CANCELLED_CUSTOMER_INSUFFICIENT_BALANCE = "8103";

    protected static function getFacadeAccessor()
    {
        return 'Bharat\LaravelGGWallet\Contracts\Factory';
    }
}