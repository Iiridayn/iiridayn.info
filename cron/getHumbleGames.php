<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

$humble = new WebsiteAPI();
$humble->login('https://www.humblebundle.com/login', array(
	'username' => $_SERVER['HIB_USERNAME'],
	'password' => $_SERVER['HIB_PASSWORD'],
));
$games = $humble->download('https://www.humblebundle.com/home');

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($games);
$xpath = new DOMXpath($doc);

// TODO: filter out the soundtrack only purchases
$class = '[contains(concat(" ", normalize-space(@class), " "), " CLASS ")]';
$nodes = $xpath->query(
	'//div'.str_replace('CLASS', 'row', $class)
	.'/div'.str_replace('CLASS', 'gameinfo', $class)
	.'/div'.str_replace('CLASS', 'title', $class)
	.'/a'
);

$db->exec("DELETE FROM games WHERE system = 'humble'");

$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,url)
	VALUES (?,'humble',?)
SQL
);
foreach ($nodes as $node) {
	$sql->execute(array($node->nodeValue, $node->getAttribute('href')));
}
