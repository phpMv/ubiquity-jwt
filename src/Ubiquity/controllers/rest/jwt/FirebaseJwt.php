<?php


namespace Ubiquity\controllers\rest\jwt;

use Firebase\JWT\JWT;

class FirebaseJwt implements JwtInterface{

	private static string $secretCode;
	private static array $algorithm;

	public static function create():void {
		self::$secretCode = "secretCode";
		self::$algorithm = ["HS256"];
	}

	public static function verifyToken(string $token):bool {
		if(is_object(JWT::decode($token, self::$secretCode, self::$algorithm)))
			return true;
		return false;
	}

	public static function getPayload(string $token):mixed {
		$decoded = JWT::decode($token, self::$secretCode, self::$algorithm);
		return $decoded;
	}

	public static function generateToken(array $claims):void {
		$token = JWT::encode($claims, self::$secretCode);
		print(\json_encode(['token' => $token ]));
	}
}