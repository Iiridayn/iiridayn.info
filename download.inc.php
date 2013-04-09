<?php

class WebsiteAPI {
	protected $cookiejar;
	public $login_response;

	function __construct($url, $post = array()) {
		$this->cookiejar = tempnam(sys_get_temp_dir(), 'iiridayn.info-cookie');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiejar);
		//curl_setopt($ch, CURLOPT_USERAGENT, '');
		$this->login_response = curl_exec($ch);
		curl_close($ch);
	}

	function __destruct() {
		unlink($this->cookiejar);
	}

	function download($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiejar);
		//curl_setopt($ch, CURLOPT_USERAGENT, '');
		$doc = curl_exec($ch);
		curl_close($ch);
		return $doc;
	}
}
