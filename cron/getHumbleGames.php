<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

$humble = new WebsiteAPI('https://www.humblebundle.com');
//*
$humble->login('/login', array(
	'username' => $_SERVER['HIB_USERNAME'],
	'password' => $_SERVER['HIB_PASSWORD'],
));
//*/
$games = $humble->request('/home', __DIR__.'/humble');

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($games);
$xpath = new DOMXpath($doc);

// TODO: audio only downloads are filtered, but movies are per platform
// Filter by requiring that one of windows/mac/linux/android have a download
$class = function ($class) {
	return '[contains(concat(" ", normalize-space(@class), " "), " '.$class.' ")]';
};
$nodes = $xpath->query(
	'//div'.$class('row')
	.'/div'.$class('gameinfo').'['
		.'following-sibling::div'.$class('windows').'/div'.$class('download')
		.' or following-sibling::div'.$class('mac').'/div'.$class('download')
		.' or following-sibling::div'.$class('linux').'/div'.$class('download')
		.' or following-sibling::div'.$class('android').'/div'.$class('download')
	.']/div'.$class('title').'/a'
);

$db->exec("DELETE FROM games WHERE system = 'humble'");

$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,url)
	VALUES (?,'humble',?)
SQL
);

// TODO: Dear Esther shows up three times, due to having three "name"s
$names = array(); // can prevent (most) duplicates by checking name
foreach ($nodes as $node) {
	if (in_array($node->nodeValue, $names)) continue;
	$names []= $node->nodeValue;
	$sql->execute(array($node->nodeValue, $node->getAttribute('href')));
}

//unlink(__DIR__.'/humble');
