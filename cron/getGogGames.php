<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

// To preserve the downloaded content for debugging purposes, comment the
// $rmdir line at the bottom of the file to prevent subsequent requests.
// Note that login problems still need the file_put_content statements for now
// TODO: we only need to log in if requesting something secure, login closure?
$filedir = __DIR__.'/gog';
if (!is_dir($filedir)) mkdir($filedir);

$gog = new WebsiteAPI();

//*
$ajax = $gog->ajax('http://www.gog.com/user/ajax', array(
	'a' => 'get', 'c' => 'frontpage', 'p1' => 'false', 'p2' => 'false', 'auth' => ''
));
//file_put_contents($filedir.'/ajax', $ajax);
$csrf = json_decode($ajax)->buk;

$login = $gog->login('https://secure.gog.com/login', array(
	'log_email' => $_SERVER['GOG_USERNAME'],
	'log_password' => $_SERVER['GOG_PASSWORD'],
	'redirectOk' => '/',
	'unlockSettings' => '1',
	'buk' => $csrf,
));
//file_put_contents($filedir.'/login', $login);
//*/

$gog->baseurl = 'https://secure.gog.com';
$games = $gog->request('/account/games', $filedir.'/games');

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($games);
$xpath = new DOMXpath($doc);

$nodes = $xpath->query('//div[@id="shelfGamesList"]/div[@data-gameid]');

$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,url,gog_id)
	VALUES (?,'gog',?,?)
	ON DUPLICATE KEY UPDATE name=VALUES(name), url=VALUES(url)
SQL
);

$query = '/account/ajax?a=gamesShelfDetails&g=';
foreach ($nodes as $node) {
	// have to query for each game
	$gameid = $node->getAttribute('data-gameid');
	$game = json_decode($gog->request(
		$query.$gameid, $filedir.'/game-'.$gameid
	))->details->html;

	$gamedoc = new DOMDocument();
	$gamedoc->loadHTML('<?xml encoding="utf-8">'.$game);
	$gamexpath = new DOMXpath($gamedoc);

	$gamenode = $gamexpath->query('//h2/a')->item(0);
	$sql->execute(array(
		trim($gamenode->nodeValue),
		$gamenode->getAttribute('href'),
		$gameid,
	));
}

$rmdir = function ($dir) use (&$rmdir) {
	if (!is_dir($dir)) return false;
	foreach (glob($dir.'/*') as $file) {
		if (is_dir($file))
			$rmdir($file);
		else unlink($file);
	}
	return rmdir($dir);
};
$rmdir($filedir);
