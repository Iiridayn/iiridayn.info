<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

$filedir = __DIR__.'/humble';
if (!is_dir($filedir)) mkdir($filedir);

$humble = new WebsiteAPI('https://www.humblebundle.com');
//*
// TODO: lazy login in WebsiteAPI?
$humble->login('/login', array(
	'username' => $_SERVER['HIB_USERNAME'],
	'password' => $_SERVER['HIB_PASSWORD'],
));
//*/

$keypage = $humble->request('/home', $filedir.'/home');
$keypos = strpos($keypage, "gamekeys: ")+10;
$keystring = substr($keypage, $keypos, strpos($keypage, ']', $keypos)-$keypos+1);
$keys = json_decode($keystring);

$db->exec("DELETE FROM games WHERE system = 'humble'");

$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,url)
	VALUES (?,'humble',?)
SQL
);

// sometimes the bundle provides a game I already own
$names = array();
foreach ($keys as $key) {
	$json = $humble->request("/api/v1/order/".$key, $filedir."/".$key);
	$order = json_decode($json);
	foreach ($order->subproducts as $game) {
		$executable = false;
		foreach ($game->downloads as $download) {
			// Note: "Indie Game: The Movie" passes this check.
			if (in_array($download->platform, array("windows", "mac", "linux", "android"))) {
				$executable = true;
				break;
			}
		}
		if (!$executable || isset($names[$game->human_name])) continue;
		$names[$game->human_name] = $key;
		$sql->execute(array($game->human_name, $game->url));
	}
}
var_dump($names);

$rmdir = function ($dir) use (&$rmdir) {
	if (!is_dir($dir)) return false;
	foreach (glob($dir.'/*') as $file) {
		if (is_dir($file))
			$rmdir($file);
		else unlink($file);
	}
	return rmdir($dir);
};
// keep the keys, flush the keylist; save server time
//$rmdir($filedir);
unlink($filedir."/home");
