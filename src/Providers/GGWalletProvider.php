<?php

namespace Bharat\LaravelGGWallet\Providers;

use Dompdf\Exception;
use Illuminate\Http\Request;
require __DIR__.'/../../lib/gg_encdec.php';


class GGWalletProvider{

	protected $request;
	protected $response;
	protected $gg_txn_url;
	protected $gg_txn_status_url;
	protected $gg_balance_check_url;

	protected $merchant_key;
	protected $merchant_id; 


	public function __construct(Request $request, $config){
		$this->request = $request;
		
		if ($config['env'] == 'production') {
			$domain = 'wallet.globalgarner.com';
		}else{
			$domain = 'wallet.gg.com';
		}
		$this->gg_txn_url = 'https://'.$domain.'/oltp-web/processTransaction';
		$this->gg_txn_status_url = 'https://'.$domain.'/oltp/HANDLER_INTERNAL/TXNSTATUS';
		$this->gg_refund_url = 'https://'.$domain.'/oltp/HANDLER_INTERNAL/REFUND';
		$this->gg_balance_check = 'https://'.$domain.'/oltp/HANDLER_INTERNAL/checkBalance';

		$this->merchant_key = $config['merchant_key'];
		$this->merchant_id = $config['merchant_id'];
	}

	public function response(){
		$checksum = $this->request->get('SIGNATURE');
        if(verifychecksum_e($this->request->all(), $this->merchant_key, $checksum) == "TRUE"){
            return (object) $this->request->all();
        }
        throw new Exception('Invalid checksum');
	}


	public function api_call($url, $params){
		$jsonResponse = "";
		$responseParamList = array();
		$JsonData =json_encode($params);
		$postData = 'JsonData='.urlencode($JsonData);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
			'Content-Type: application/json', 
			'Content-Length: ' . strlen($postData))                                                                       
		);  
		$jsonResponse = curl_exec($ch);   
		return $responseParamList = json_decode($jsonResponse,true);
	}


}