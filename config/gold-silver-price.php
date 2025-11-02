<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gold & Silver Price Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the behavior of the gold and silver price fetching system
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Source URL
    |--------------------------------------------------------------------------
    |
    | The URL to fetch gold and silver prices from
    |
    */
    'source_url' => env('GOLD_PRICE_SOURCE_URL', 'https://fenegosida.org'),

    /*
    |--------------------------------------------------------------------------
    | Filament Resource
    |--------------------------------------------------------------------------
    |
    | Configure the Filament resource
    |
    */
    'filament' => [
        'enabled' => true,
        'navigation_group' => 'Content',
        'navigation_icon' => 'heroicon-o-currency-dollar',
        'navigation_label' => 'Gold & Silver Prices',
        'navigation_sort' => null,
        'widgets' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Table Name
    |--------------------------------------------------------------------------
    |
    | The name of the table to store gold and silver prices
    |
    */
    'table_name' => 'gold_prices',

    /*
    |--------------------------------------------------------------------------
    | Fetching Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the HTTP client behavior for fetching prices
    |
    */
    'fetching' => [
        'timeout' => 90,
        'connect_timeout' => 60,
        'retry_times' => 1,
        'retry_delay' => 5000,
        'verify_ssl' => false,
    ],
];
