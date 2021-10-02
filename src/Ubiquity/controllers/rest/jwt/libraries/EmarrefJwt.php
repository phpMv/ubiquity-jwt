<?php


namespace Ubiquity\controllers\rest\jwt\libraries;


use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Claim\PublicClaim;
use Emarref\Jwt\Claim\Subject;
use Emarref\Jwt\Encryption\Asymmetric;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Encryption\Symmetric;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use Ubiquity\controllers\rest\jwt\JwtInterface;
use Ubiquity\controllers\rest\jwt\traits\BaseJwt;

class EmarrefJwt extends BaseJwt implements JwtInterface{

    private Jwt $jwt;
    private Context $context;

    public function __construct(&$config) {
        parent::__construct($config);
        $this->jwt = new Jwt();
    }

    public function verifyToken(string $token):bool {
        $token = $this->jwt->deserialize($token);
		$encryption = Factory::create(new $this->algorithm($this->secretCode));
		$context = new Context($encryption);
        return $this->jwt->verify($token, $this->context);
    }

    public function getPayload(string $token):mixed {
        $token = $this->jwt->deserialize($token);
        try{
            $payload = $token->getPayload();
            if($payload){
                return \json_decode($payload->getClaims()->jsonSerialize());
            }
        }catch(\Exception $e){
            return false;
        }
    }

    private function _generateToken(array $claims, Symmetric|Asymmetric $encryption):string {
		$token = new Token();
		foreach($claims as $claim => $value){
			$token->addClaim(new PublicClaim($claim, $value));
		}
		return $this->jwt->serialize($token, $encryption);
	}

    public function generateToken(array $claims):void {
		$algorithmClass = "Emarref\Jwt\Algorithm\\$this->algorithm";
		$encryption = Factory::create(new $algorithmClass($this->secretCode));
        $tokens["accessToken"] = $this->_generateToken($claims, $encryption);
        if($this->refreshToken){
			$encryption = Factory::create(new $algorithmClass($this->refreshToken));
        	$tokens["refreshToken"] = $this->_generateToken($claims, $encryption);
		}
        print(\json_encode($tokens));
    }
}