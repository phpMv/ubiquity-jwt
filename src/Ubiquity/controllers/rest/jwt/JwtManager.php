<?php


namespace Ubiquity\controllers\rest\jwt;


class JwtManager {
	private static array $defaultConfig=[];

	public static array $supportedLibraries = [
		'Emarref' => 'Emarref\Jwt\Jwt',
		'Firebase' => 'Firebase\JWT\JWT',
		'Lcobucci' => 'Lcobucci\JWT\Configuration',
	];

	public static ?JwtInterface $library = null;

	public static function start(&$config):void {
		self::$library??=self::getLibrary($config['jwt']??self::$defaultConfig);
	}
	
	private static function getLibraryName():string{
	    foreach(self::$supportedLibraries as $libraryName => $className){
	        if(\class_exists($className)){
	            return "Ubiquity\\controllers\\rest\\jwt\\libraries\\{$libraryName}Jwt";
	        }
	    }
	    throw new UbiquityJWTException("No JWT library available!");
	}

	public static function getLibrary($config):JwtInterface {
	    $libraryName=(self::$supportedLibraries[$config['jwt']['library']??false])??self::getLibraryName();
		return new $libraryName($config['jwt']);
	}
}