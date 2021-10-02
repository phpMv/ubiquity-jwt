<?php


namespace Ubiquity\controllers\rest\jwt\libraries;

use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Ubiquity\controllers\rest\jwt\traits\BaseJwt;
use Ubiquity\controllers\rest\jwt\JwtInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;

class LcobucciJwt extends BaseJwt implements JwtInterface{

	public function __construct(&$config) {
		parent::__construct($config);
		$algorithmClass = "Lcobucci\JWT\Signer\\$this->algorithm";
		$this->algorithm = new $algorithmClass();
		$this->secretCode = InMemory::plainText($this->secretCode);
		if($this->refreshToken){
			$this->refreshToken = InMemory::plainText($this->refreshToken);
		}
	}

	public function verifyToken(string $token):bool {
		$token = $this->config->parser()->parse($token);
		$constraint = new SignedWith($this->signer, $this->secretCode);
		try {
			$this->config->validator()->validate($token, $constraint);
			return true;
		} catch (RequiredConstraintsViolated $e) {
			return false;
		}
	}

	public function getPayload(string $token):mixed {
		$token = $this->config->parser()->parse($token);
		return $token->claims();
	}

	private function _generateToken(array $claims, Configuration $encryption):string {
		$token = $encryption->builder();
		foreach($claims as $claim => $value){
			$token->withClaim($claim, $value);
		}
		return $token->getToken($encryption->signer(), $encryption->signingKey())->toString();
	}

	public function generateToken(array $claims):void {
		$tokens["accessToken"] = $this->_generateToken($claims, Configuration::forSymmetricSigner($this->algorithm, $this->secretCode));
		if($this->refreshToken){
			$tokens["refreshToken"] = $this->_generateToken($claims, Configuration::forSymmetricSigner($this->algorithm, $this->refreshToken));
		}
		print(\json_encode($tokens));
	}

}