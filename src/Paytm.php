<?php

namespace AniketIN\Paytm;

use paytm\paytmchecksum\PaytmChecksum;


class Paytm
{
    protected $merchantId;
    protected $merchantKey;

    public function __construct() {
        $this->merchantId = config('paytm.merchant_id');
        $this->merchantKey = config('paytm.merchant_key');
    }

    public function receive($params)
    {
        $checksum = PaytmChecksum::generateSignature($params, $this->merchantKey);
        

        dd($checksum);

        return $params;
    }
}
