<?php
final class Encryption {
	private $key;

	public function __construct($key) {
		$this->key = hash('sha256', $key, true);
	}

	public function encrypt($value) {
		return strtr(base64_encode(@openssl_encrypt($value, 'aes-128-cbc', hash('sha256', $this->key, true))), '+/=', '-_,');
	}

	public function decrypt($value) {
		return trim(openssl_decrypt(base64_decode(strtr($value, '-_,', '+/=')), 'aes-128-cbc', hash('sha256', $this->key, true)));
	}
}