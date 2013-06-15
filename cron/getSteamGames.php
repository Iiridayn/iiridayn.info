<?php
require_once(dirname(__DIR__).'/environment.inc.php');
require_once(dirname(__DIR__).'/db.inc.php');
require_once(dirname(__DIR__).'/download.inc.php');

$xml = WebsiteAPI::page(
	'http://steamcommunity.com/profiles/76561198017855079/games/?tab=all&xml=1'
);
$data = new SimpleXMLElement($xml);

$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,gametime,url,steam_id)
	VALUES (?,'steam',?,?,?)
	ON DUPLICATE KEY UPDATE name=VALUES(name), gametime=VALUES(gametime), url=VALUES(url)
SQL
);
foreach ($data->games->game as $game) {
	$sql->execute(array($game->name, $game->hoursOnRecord, $game->storeLink, $game->appID));
}
