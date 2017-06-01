<?php
namespace Bharat\LaravelGGWallet\Providers;
use Illuminate\Http\Request;

class StatusCheckProvider extends GGWalletProvider{
	private $parameters = null;
    private $response;

	public function prepare($params = array()){
		$defaults = [
			'order' => NULL,
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


	public function check(){
		if ($this->parameters == null) {
			throw new \Exception("prepare() method not called");
		}
		return $this->beginTransaction();
	}
	

	private function beginTransaction(){

		$params = [
			'MID' => $this->merchant_id,
			'ORDER_ID' => $this->parameters['order']
		];
		$this->response = $this->api_call($this->gg_txn_status_url, $params);
		return $this;
	}

    public function response(){
        return $this->response;
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