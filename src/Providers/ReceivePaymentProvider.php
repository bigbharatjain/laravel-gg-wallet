<?php

namespace Bharat\LaravelGGWallet\Providers;
use Bharat\LaravelGGWallet\Facades\GGWallet;
use Illuminate\Http\Request; 

class ReceivePaymentProvider extends GGWalletProvider{

	private $parameters = null;

    public function prepare($params = array()){
		$defaults = [
			'order' => NULL,
			'user' => NULL,
			'amount' => NULL,
            'callback_url' => NULL,
            'email' => NULL,
            'mobile_number' => NULL,
		];

		$_p = array_merge($defaults, $params);
		foreach ($_p as $key => $value) {

			if ($value == NULL) {
				
				throw new \Exception(' \''.$key.'\' parameter not specified in array passed in prepare() method');
				
				return false;
			}
		}
		$this->parameters = $_p;
		return $this;
	}

	public function receive(){
		if ($this->parameters == null) {
			throw new \Exception("prepare() method not called");
		}
		return $this->beginTransaction();
	}

	private function beginTransaction(){
		$params = [
			'MID' => $this->merchant_id,
			'ORDER_ID' => $this->parameters['order'],
			'CUST_ID' => $this->parameters['user'],
			'TXN_AMOUNT' => $this->parameters['amount'],
			'CALLBACK_URL' => $this->parameters['callback_url'],
            'MOBILE_NO' => $this->parameters['mobile_number'],
            'EMAIL' => $this->parameters['email'],
        ];
		return view('ggwallet::transact')->with('params', $params)->with('txn_url', $this->gg_txn_url)->with('checkSum', getChecksumFromArray($params, $this->merchant_key));
	}


	public function isSuccessful(){

        if($this->response()->STATUS == GGWallet::STATUS_SUCCESSFUL){
            return true;
        }
        return false;
    }

    public function isFailed(){
        if ($this->response()->STATUS == GGWallet::STATUS_FAILURE) {
            return true;
        }
        return false;
    }

    public function isOpen(){
        if ($this->response()->STATUS == GGWallet::STATUS_OPEN){
            return true;
        }
        return false;
    }

    public function getOrderId(){
        return $this->response()->ORDERID;
    }
    public function getTransactionId(){
        return $this->response()->TXNID;
    }

}