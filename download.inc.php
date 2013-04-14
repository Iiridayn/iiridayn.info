<?php

class WebsiteAPI {
	protected $cookiejar;
	public static $useragent = 'WebsiteAPI (iiridayn.info)';

	function __construct() {
		$this->cookiejar = tempnam(sys_get_temp_dir(), 'iiridayn.info-cookie');
	}

	function __destruct() {
		unlink($this->cookiejar);
	}

	function login($url, $post) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, self::$useragent);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiejar);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiejar);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$response =  curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	function ajax($url, $post) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, self::$useragent);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiejar);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiejar);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Requested-With' => 'XMLHttpRequest'));
		$response =  curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	function download($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, self::$useragent);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiejar);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiejar);
		$doc = curl_exec($ch);
		curl_close($ch);
		return $doc;
	}

	static function page($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, self::$useragent);
		$page = curl_exec($ch);
		curl_close($ch);
		return $page;
	}
}
