<?php
namespace Ubiquity\controllers\rest\jwt;

interface JwtInterface {
    public static function create():void;
	public static function verifyToken(string $token):bool;
	public static function getPayload(string $token):mixed;
	public static function generateToken(array $claims):void;
}