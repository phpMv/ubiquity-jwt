<?php

namespace Ubiquity\controllers\rest\jwt\traits;

use Ubiquity\controllers\rest\jwt;

trait JwtTrait {

	static public $jwt;

	public static function setLibrary($library = jwt\JwtController::class){
		JwtTrait::$jwt = new jwt\JwtController();
	}

	protected function checkTokenValidity($data){
		JwtTrait::$jwt = new jwt\JwtController();
		if(JwtTrait::$jwt->verifyToken($data))
			return true;
		return false;
	}
}

