<?php

namespace AniketIN\Paytm;

use paytm\paytmchecksum\PaytmChecksum;


class Paytm
{
    protected $merchantId;
    protected $merchantKey;
    protected $channel;
    protected $params;
    protected $domain;

    public function __construct() {
        $this->merchantId = config('paytm.merchant_id');
        $this->merchantKey = config('paytm.merchant_key');
        $this->channel = config('paytm.channel');
        $this->website = config('paytm.merchant_website');
        $this->domain = config('paytm.env') == 'production' ? "https://securegw.paytm.in" : "https://securegw-stage.paytm.in";
    }

    public function checkout(array $params)
    {
        $this->params = $params;

        $this->params['MID'] = $this->merchantId;
        $this->params['CHANNEL_ID'] = $this->channel;
        $this->params['WEBSITE'] = $this->website;

        $this->mergeChecksum();
    
        return view('paytm::checkout-form', [
            'params' => $this->params,
            'txn_url' => "{$this->domain}/order/process",
        ]);
    }

    protected function mergeChecksum()
    {
        $this->params['CHECKSUMHASH'] = PaytmChecksum::generateSignature($this->params, $this->merchantKey);
    }
}
