<?php


namespace Ubiquity\controllers\rest\jwt\libraries;


use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Claim\PublicClaim;
use Emarref\Jwt\Encryption\Asymmetric;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Encryption\Symmetric;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use Ubiquity\controllers\rest\jwt\JwtInterface;

class EmarrefJwt implements JwtInterface{

    private string $secretCode;
    private Jwt $jwt;
    private AlgorithmInterface $algorithm;
    private Symmetric|Asymmetric $encryption;
    private Context $context;

    public function __construct() {
        $this->secretCode = "secretCode";
        $this->jwt = new Jwt();
        $this->algorithm = new Hs256($this->secretCode);
        $this->encryption = Factory::create($this->algorithm);
        $this->context = new Context($this->encryption);
    }

    public function verifyToken(string $token):bool {
        $token = $this->jwt->deserialize($token);
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

    public function generateToken($claims):void {
        $token = new Token();
        foreach($claims as $claim => $value){
            $token->addClaim(new PublicClaim($claim, $value));
        }
        print(\json_encode(['token' => $this->jwt->serialize($token, $this->encryption)]));
    }
}