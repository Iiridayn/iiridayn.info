<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

/**
 * This code is fairly brittle, so I've left the debugging statements commented out
 */

$gog = new WebsiteAPI();

$homepage = $gog->request('http://www.gog.com/');
//file_put_contents('goghomepage', $homepage);

$ajax = $gog->ajax('http://www.gog.com/user/ajax', array(
	'a' => 'get', 'c' => 'frontpage', 'p1' => 'false', 'p2' => 'false', 'auth' => ''
));
//file_put_contents('gogajax', $ajax);
$csrf = json_decode($ajax)->buk;

$login = $gog->login('https://secure.gog.com/login', array(
	'log_email' => $_SERVER['GOG_USERNAME'],
	'log_password' => $_SERVER['GOG_PASSWORD'],
	'redirectOk' => '/',
	'unlockSettings' => '1',
	'buk' => $csrf,
));
//file_put_contents('goglogin', $login);

$games = $gog->request('https://secure.gog.com/account/games');
//file_put_contents('goggames', $games);

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

$query = 'https://secure.gog.com/account/ajax?a=gamesShelfDetails&g=';
foreach ($nodes as $node) {
	// have to query for each game
	$gameid = $node->getAttribute('data-gameid');
	$game = json_decode($gog->download($query.$gameid))->details->html;
	//file_put_contents('goggame-'.$node->getAttribute('data-gameid'), $game);

	$gamedoc = new DOMDocument();
	$gamedoc->loadHTML('<?xml encoding="utf-8">'.$game);
	$gamexpath = new DOMXpath($gamedoc);

	$gamenode = $gamexpath->query('//h2/a')->item(0);
	$sql->execute(array(
		trim($gamenode->nodeValue),
		$gamenode->getAttribute('href'),
		$gameid,
	));

	usleep(200000); // 0.2 second sleep to be more polite
}
