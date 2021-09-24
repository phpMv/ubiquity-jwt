<?php


namespace Ubiquity\controllers\rest\jwt;


use Emarref\Jwt\Algorithm\AlgorithmInterface;
use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Claim\PublicClaim;
use Emarref\Jwt\Encryption\Asymmetric;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Encryption\Symmetric;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Emarref\Jwt\Verification\Context;
use models\User;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;

class EmarrefJwt implements JwtInterface{

    private static string $secretCode;
    private static Jwt $jwt;
    private static AlgorithmInterface $algorithm;
    private static Symmetric|Asymmetric $encryption;
    private static Context $context;

    public static function create():void {
        self::$secretCode = "secretCode";
        self::$jwt = new Jwt();
        self::$algorithm = new Hs256(self::$secretCode);
        self::$encryption = Factory::create(self::$algorithm);
        self::$context = new Context(self::$encryption);
    }

    public static function verifyToken(string $token):bool {
        $token = self::$jwt->deserialize($token);
        return self::$jwt->verify($token, self::$context);
    }

    public static function getPayload(string $token):mixed {
        $token = self::$jwt->deserialize($token);
        try{
            $payload = $token->getPayload();
            if($payload){
                return \json_decode($payload->getClaims()->jsonSerialize());
            }
        }catch(\Exception $e){
            return false;
        }
    }

    public static function generateToken($claims):void {
        $token = new Token();
        foreach($claims as $claim => $value){
            $token->addClaim(new PublicClaim($claim, $value));
        }
        print(\json_encode(['token' => self::$jwt->serialize($token, self::$encryption)]));
    }
}