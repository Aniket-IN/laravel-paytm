<?php

// config for AniketIN/Paytm

return [
    'env' => env('PAYTM_ENVIRONMENT'), // values : (local | production)
    'merchant_id' => env('PAYTM_MERCHANT_ID'),
    'merchant_key' => env('PAYTM_MERCHANT_KEY'),
    'merchant_website' => env('PAYTM_MERCHANT_WEBSITE'),
    'channel' => env('PAYTM_CHANNEL'),
    'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
];
