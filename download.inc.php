<?php

class WebsiteAPI {
	public static $useragent = 'WebsiteAPI';
	public static $delay = 1;
	public $baseurl = null;

	protected $cookiejar;
	protected static $lastrequest = 0;

	/**
	 * Baseurl will be prepended to every URL - recommend no trailing '/'
	 */
	public function __construct($baseurl = null) {
		$this->baseurl = $baseurl;
		$this->cookiejar = tempnam(sys_get_temp_dir(), self::$useragent.'cookie');
	}

	public function __destruct() {
		unlink($this->cookiejar);
	}

	public function login($url, array $post, $file = null) {
		return $this->post($url, $post, $file, array(
			CURLOPT_HEADER => 1,
		));
	}

	public function ajax($url, array $post, $file = null) {
		return $this->post($url, $post, $file, array(
			CURLOPT_HTTPHEADER => array('X-Requested-With' => 'XMLHttpRequest'),
		));
	}

	public function post($url, array $post, $file = null, array $options = array()) {
		return $this->request(
			$url, $file,
			$options + array(
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $post,
			)
		);
	}

	// = download
	public function request($url, $file = null, array $options = array()) {
		return self::page(
			$this->baseurl.$url, $file,
			$options + array(
				CURLOPT_COOKIEFILE => $this->cookiejar,
				CURLOPT_COOKIEJAR => $this->cookiejar,
			)
		);
	}

	public static function page($url, $file = null, array $options = array()) {
		if ($file && file_exists($file)) {
			return file_get_contents($file);
		}

		$sincelast = time() - self::$lastrequest;
		if ($sincelast < self::$delay) {
			sleep(self::$delay - $sincelast);
		}
		self::$lastrequest = time();

		$ch = curl_init();
		curl_setopt_array($ch, $options + array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => self::$useragent,
		));

		$page = curl_exec($ch);
		if (curl_errno($ch)) {
			die('Curl error: '.curl_error($ch)."\n");
		}
		curl_close($ch);

		if ($file) {
			file_put_contents($file, $page);
		}
		return $page;
	}
}
