<?php


namespace Ubiquity\controllers\rest\jwt;


use function Composer\Autoload\includeFile;

class JwtManager {

	public static array $supportedLibraries = [
		"emarref/jwt" => "Emarref\Jwt\Jwt",
		"firebase/php-jwt" => "Firebase\JWT\JWT"
	];
	public static string $library = "";

	public static function create():void {
		if(self::$library == "")
			self::setLibrary();
		forward_static_call([JwtManager::$library, 'create']);
	}

	public static function setLibrary($libraryName = null):void {
		if($libraryName == null){
			foreach(self::$supportedLibraries as $libraryName => $className){
				if(class_exists($className)){
					$libraryName = explode('\\', $className)[0];
					self::$library = "Ubiquity\\controllers\\rest\\jwt\\".$libraryName."Jwt";
					break;
				}
			}
		}
		else{
			self::$library = "Ubiquity\\controllers\\rest\\jwt\\".$libraryName."Jwt";
		}
	}
}