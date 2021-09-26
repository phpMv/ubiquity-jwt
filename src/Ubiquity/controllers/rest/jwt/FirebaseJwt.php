<?php


namespace Ubiquity\controllers\rest\jwt\libraries;

use Firebase\JWT\JWT;

class FirebaseJwt extends BaseJwt implements JwtInterface{

	private array $algorithm;

	public function __construct__() {
		parent::__construct("secretCode");
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

	public function generateToken(array $claims):void {
		$token = JWT::encode($claims, $this->secretCode);
		print(\json_encode(['token' => $token ]));
	}
}