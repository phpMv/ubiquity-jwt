<?php


namespace Ubiquity\controllers\rest\jwt\traits;


use Emarref\Jwt\Algorithm\AlgorithmInterface;

class BaseJwt {
	protected $secretCode;
	protected $refreshToken;
	protected $algorithm;

	public function __construct(&$config){
		$this->secretCode = $config['jwt']['secretCode'];
		$this->algorithm = $config['jwt']['algorithm'];
		$this->refreshToken = (\array_key_exists("refreshToken",$config["jwt"])) ? $config["jwt"]["refreshToken"] : false;
	}
}