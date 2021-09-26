<?php


namespace Ubiquity\controllers\rest\jwt;


class JwtManager {

	public static array $supportedLibraries = [
		"emarref/jwt" => "Emarref\Jwt\Jwt",
		"firebase/php-jwt" => "Firebase\JWT\JWT"
	];

	public static ?JwtInterface $library = null;

	public static function start():void {
		if(!self::$library){
			self::setLibrary();
		}
	}

	public static function setLibrary($libraryName = null):void {
		if($libraryName == null){
			foreach(self::$supportedLibraries as $libraryName => $className){
				if(class_exists($className)){
					$libraryName = explode('\\', $className)[0];
					$libraryName = "Ubiquity\\controllers\\rest\\jwt\\libraries\\".$libraryName."Jwt";
				}
			}
		}
		else{
			$libraryName = "Ubiquity\\controllers\\rest\\jwt\\libraries\\".$libraryName."Jwt";
		}
		self::$library = new $libraryName();
	}
}