<?php


namespace Ubiquity\controllers\rest\jwt;


use function Composer\Autoload\includeFile;

class JwtManager {

	public static array $supportedLibraries = [
		"Emarref" => "Emarref\Jwt\Jwt",
		"Firebase" => "Firebase\JWT\JWT",
		"Lcobucci" => "Lcobucci\JWT\Configuration",
	];

	public static ?JwtInterface $library = null;

	public static function start($config):void {
		if(!self::$library){
			self::setLibrary($config);
		}
	}

	public static function setLibrary(&$config):void {
		if($config['jwt']['library'] == null){
			foreach(self::$supportedLibraries as $libraryName => $className){
				if(class_exists($className)){
					$libraryName = explode('\\', $className)[0];
					$libraryName = "Ubiquity\\controllers\\rest\\jwt\\libraries\\".$libraryName."Jwt";
				}
			}
		}
		else{
			$libraryName = "Ubiquity\\controllers\\rest\\jwt\\libraries\\".$config['jwt']['library']."Jwt";
		}
		self::$library = new $libraryName($config);
	}
}