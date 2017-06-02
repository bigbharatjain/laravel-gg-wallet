<?php

namespace Bharat\LaravelGGWallet\Providers;
use Illuminate\Http\Request;

class GGAppProvider extends GGWalletProvider{

	public function generate(Request $request){
		$checksum = getChecksumFromArray($request->all(), $this->merchant_key);
		return response()->json([ 'SIGNATURE' => $checksum, 'ORDER_ID' => $request->get('ORDER_ID'), 'payt_STATUS'  => '1' ]);
	}

	public function verify(Request $request, $success = null, $error = null){
		$paramList = $request->all();
		$return_array = $request->all();
		$ggChecksum = $request->get('SIGNATURE');

		$isValidChecksum = verifychecksum_e($paramList, $this->merchant_key, $ggChecksum);
		
		if ($isValidChecksum) {
			if ($success != null) {
				$success();
			}
		}else{
			if ($error != null) {
				$error();
			}
		}

		$return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
		unset($return_array["SIGNATURE"]);
		$encoded_json = htmlentities(json_encode($return_array));

		return view('ggwallet::app_redirect')->with('json', $encoded_json);
	}


}
