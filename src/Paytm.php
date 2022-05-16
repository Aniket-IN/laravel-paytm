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

    protected function mergeChecksum(string $as = 'CHECKSUMHASH')
    {
        $this->params[$as] = PaytmChecksum::generateSignature($this->params, $this->merchantKey);
    }

    protected function verifyChecksum(Request $request)
    {
        $isValid = PaytmChecksum::verifySignature($request->all(), $this->merchantKey, $request->CHECKSUMHASH);
        
        if (!$isValid) {
            throw new Error("Invalid checksum! data might be tampered.");
        }
    }

    public function verify(Request $request)
    {
        $this->verifyChecksum($request);
        return $request;
    }

    public function status(string $orderId)
    {
        $this->params['MID'] = $this->merchantId;
        $this->params['ORDERID'] = $orderId;
        
        $this->mergeChecksum();

        $response = Http::post("{$this->domain}/merchant-status/getTxnStatus", $this->params);

        return $response;
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

    public function refund(array $params)
    {
        $this->params = $params;

        $this->params["TXNTYPE"] = 'REFUND';
        $this->params["MID"] = $this->merchantId;

        $this->mergeChecksum('CHECKSUM');

        return Http::post("{$this->domain}/refund/HANDLER_INTERNAL/REFUND", $this->params);
    }

    public function refundStatus(array $params)
    {
        $this->params = $params;

        $this->params["MID"] = $this->merchantId;

        $this->mergeChecksum('CHECKSUM');

        return Http::post("{$this->domain}/refund/HANDLER_INTERNAL/getRefundStatus", $this->params);
    }

    
}
