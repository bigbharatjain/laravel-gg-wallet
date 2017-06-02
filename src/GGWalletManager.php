<?php

namespace Bharat\LaravelGGWallet;

use Illuminate\Support\Manager;
use Illuminate\Http\Request;
class GGWalletManager extends Manager implements Contracts\Factory{
	private $config;

	public function with($driver){
		return $this->driver($driver);
	}

	protected function createReceiveDriver(){
		$this->config = $this->app['config']['services.gg-wallet'];

		return $this->buildProvider(
			'Bharat\LaravelGGWallet\Providers\ReceivePaymentProvider',
			$this->config
			);
	}

	protected function createStatusDriver(){
		$this->config = $this->app['config']['services.gg-wallet'];
		return $this->buildProvider(
			'Bharat\LaravelGGWallet\Providers\StatusCheckProvider',
			$this->config
			);
	}

	protected function createBalanceDriver(){
		$this->config = $this->app['config']['services.gg-wallet'];
		return $this->buildProvider(
			'Bharat\LaravelGGWallet\Providers\BalanceCheckProvider',
			$this->config
			);
	}

	protected function createAppDriver(){
		$this->config = $this->app['config']['services.gg-wallet'];
		return $this->buildProvider(
			'Bharat\LaravelGGWallet\Providers\PaytmAppProvider',
			$this->config
			);
	}
	

	public function getDefaultDriver(){
		throw new \Exception('No driver was specified. - Laravel GG Wallet');
	}

	public function buildProvider($provider, $config){
		return new $provider(
			$this->app['request'],
			$config
			);
	}


	private function beginTransaction(){

		$params = [
		'MID' => $this->merchant_id,
		'ORDER_ID' => $this->parameters['order'],
		'CUST_ID' => $this->parameters['user'],
		'TXN_AMOUNT' => $this->parameters['amount']
		];
		return view('ggwallet::transact')->with('params', $params)->with('txn_url', $this->gg_txn_url)->with('checkSum', getChecksumFromArray($params, $this->merchant_key));
	}

}
