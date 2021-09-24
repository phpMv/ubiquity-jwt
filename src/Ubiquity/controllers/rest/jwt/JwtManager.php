<?php


namespace Ubiquity\controllers\rest\jwt;


use function Composer\Autoload\includeFile;

class JwtManager {

	public static array $supportedLibraries = [
		"emarref/jwt",
		"firebase/php-jwt"
	];
	public static string $library = "";

	public static function create():void {
		if(self::$library == "")
			self::setLibrary();
		forward_static_call([JwtManager::$library, 'create']);
	}

	public static function setLibrary($libraryName = null):void {
		if($libraryName == null){
			$composer = \json_decode(file_get_contents('composer.json'));
			foreach($composer->require as $name => $value){
				if(\array_search($name, self::$supportedLibraries)){
					$lib = \ucfirst(explode('/', $name)[0]);
					self::$library = "Ubiquity\\controllers\\rest\\jwt\\".$lib."Jwt";
				}
			}
		}
		else{
			self::$library = "Ubiquity\\controllers\\rest\\jwt\\".$libraryName."Jwt";
		}
	}
}