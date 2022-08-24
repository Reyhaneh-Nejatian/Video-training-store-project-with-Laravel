<?php

namespace arghavan\Payment\Gateways\Zarinpal;

use arghavan\Payment\Contracts\GatewayContract;
use arghavan\Payment\Models\Payment;
use Illuminate\Http\Request;

class ZarinpalAdaptor implements GatewayContract
{

    private $url;
    private $client;
    public function request($amount,$description)
    {
        $this->client = new Zarinpa();
        $callback = route('payments.callback');
        $result = $this->client->request("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",$amount,$description,"","",$callback,true);

        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            $this->url = $result['StartPay'];
            return $result['Authority'];
        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"]
            ];
        }
    }

    public function redirect()
    {
        $this->client->redirect($this->url);
    }

    public function getName()
    {
        return 'zarinpal';
    }

    public function verify(Payment $payment)
    {
        $this->client = new Zarinpa();

        $result = $this->client->verify("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",$payment->amount,true);

        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            return $result["RefID"];

        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"]
            ];
        }
    }

    public function getInvoiceIdFromRequest(Request $request)
    {
        return $request->Authority;
    }
}
