<?php
require_once(dirname(__DIR__).'/db.inc.php');

$ch = curl_init('http://steamcommunity.com/profiles/76561198017855079/games/?tab=all&xml=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$xml = curl_exec($ch);
if (curl_errno($ch)) {
	die('Curl error: '.curl_error($ch));
}
curl_close($ch);

$data = new SimpleXMLElement($xml);
$sql = $db->prepare(<<<SQL
	INSERT INTO games (name,system,owner,gametime,url,steam_id)
	VALUES (?,'pc','steam',?,?,?)
	ON DUPLICATE KEY UPDATE gametime=VALUES(gametime), url=VALUES(url)
SQL
);
foreach ($data->games->game as $game) {
	$sql->execute(array($game->name, $game->hoursOnRecord, $game->storeLink, $game->appID));
}
