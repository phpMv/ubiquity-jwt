<?php


namespace Ubiquity\controllers\rest\jwt\libraries;

use Firebase\JWT\JWT;
use Ubiquity\controllers\rest\jwt\JwtInterface;
use Ubiquity\controllers\rest\jwt\traits\BaseJwt;

class FirebaseJwt extends BaseJwt implements JwtInterface{

	public function __construct(&$config) {
		parent::__construct($config);
		$this->algorithm = ["HS256"];
	}

	public function verifyToken(string $token):bool {
		if(is_object(JWT::decode($token, $this->secretCode, $this->algorithm)))
			return true;
		return false;
	}

	public function getPayload(string $token):mixed {
		$decoded = JWT::decode($token, $this->secretCode, $this->algorithm);
		return $decoded;
	}

	private function _generateToken(array $claims, string $encryption):string {
		return JWT::encode($claims, $encryption);
	}

	public function generateToken(array $claims):void {
		$tokens["accessToken"] = $this->_generateToken($claims, $this->secretCode);
		if($this->refreshToken){
			$tokens["refreshToken"] = $this->_generateToken($claims, $this->refreshToken);
		}
		print(\json_encode($tokens));
	}
}