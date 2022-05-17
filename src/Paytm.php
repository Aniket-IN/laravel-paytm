<?php

namespace AniketIN\Paytm;

use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    protected function mergeChecksum($params, string $as = 'CHECKSUMHASH')
    {
        $this->params[$as] = $this->getChecksum($params);
    }

    protected function getChecksum($params)
    {
        return PaytmChecksum::generateSignature($params, $this->merchantKey);
    }

    private function mergeSignature()
    {
        $this->params['head']["signature"] = $this->getChecksum(json_encode($this->params['body']));
    }

    protected function verifyChecksum(Request $request)
    {
        $isValid = PaytmChecksum::verifySignature($request->all(), $this->merchantKey, $request->CHECKSUMHASH);
        
        if (!$isValid) {
            throw new Error("Invalid checksum! data might be tampered.");
        }
    }

    
    public function checkout(array $params)
    {
        $this->params = $params;

        $this->params['MID'] = $this->merchantId;
        $this->params['CHANNEL_ID'] = $this->channel;
        $this->params['WEBSITE'] = $this->website;
        
        $this->mergeChecksum($this->params);
    
        return view('paytm::checkout-form', [
            'params' => $this->params,
            'txn_url' => "{$this->domain}/order/process",
        ]);
    }

    public function verify(Request $request)
    {
        $this->verifyChecksum($request);
        return $request;
    }

    public function status(string $orderId)
    {
        $this->params['body']['mid'] = $this->merchantId;
        $this->params['body']['orderId'] = $orderId;
        
        $this->mergeSignature();

        $response = Http::post("{$this->domain}/v3/order/status", $this->params);

        return $response;
    }

   
    public function refund(array $params)
    {
        $this->params['body'] = $params;
        $this->params['body']["mid"] = $this->merchantId;
        $this->params['body']["txnType"] = 'REFUND';

        $this->mergeSignature();

        return Http::post("{$this->domain}/refund/apply", $this->params);
    }

    

    public function refundStatus(array $params)
    {
        $this->params['body'] = $params;
        $this->params['body']["mid"] = $this->merchantId;
        $this->params['head']["signature"] = $this->getChecksum(json_encode($this->params['body']));

        return Http::post("{$this->domain}/v2/refund/status", $this->params);
    }

    
}
