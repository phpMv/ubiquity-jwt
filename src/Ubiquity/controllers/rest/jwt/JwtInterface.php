<?php
namespace Ubiquity\controllers\rest\jwt;

interface JwtInterface {
    public function verifyToken(string $token):bool;
	public function getPayload(string $token):mixed;
	public function generateToken(array $claims):void;
}