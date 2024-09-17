<?php

return [
    /**
     * YCloud API key.
     */
    'api_key' => env('WHATSAPP_API_KEY'),

    /**
     * Default from number in E.164 format.
     */
    'from' => env('WHATSAPP_FROM'), // optional

    /**
     * Specify a number in E.164 format where all messages should be routed. This can be used in development/staging environments
     * for testing.
     */
    'debug_to' => env('WHATSAPP_DEBUG_TO'),

    /**
     * If an exception is thrown with one of these error codes, it will be caught & suppressed.
     * Specify '*' in the array, which will cause all exceptions to be suppressed.
     * Suppressed errors will not be logged or reported, but the `NotificationFailed` event will be emitted.
     *
     * @see https://docs.ycloud.com/reference/errors
     */
    'ignored_error_codes' => ['*'],

    /**
     * Proxy URL.
     */
    'proxy' => env('WHATSAPP_PROXY'),

    /**
     * Proxy CA path.
     */
    'proxy_cainfo' => env('WHATSAPP_PROXY_CAINFO'),
];
