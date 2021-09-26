<?php


namespace Ubiquity\controllers\rest\jwt\libraries;


class BaseJwt {
	protected string $secretCode;
	protected $algorithm;

	public function __construct(string $secretCode){
		$this->secretCode = $secretCode;
	}
}