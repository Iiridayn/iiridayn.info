<?php
ini_set('display_errors', 1);

#phpinfo();die();
/*
$w = stream_get_wrappers();
echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "\n";
echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "\n";
echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "\n";
echo 'wrappers: ', var_dump($w);
die();
*/

// @TODO need some kind of cache refresh
$filename = basename($_SERVER['QUERY_STRING']);
$filepath = "cache/".$filename.".ico";

// Create it manually w/0777 instead
if (!file_exists("cache") && !mkdir("cache")) {
	http_response_code(500);
	die("Misconfigured server - could not write.");
}

// Apache sent us here because it didn't find the file.
// Suffers from the thundering herd problem... not a concern atm
$icons = getIcons(); // see below for list
if (!isset($icons[$filename])) {
	http_response_code(404);
	die();
}

$icon = file_get_contents($icons[$filename]);
if (false === $icon) {
	http_response_code(404);
	die();
}

if (false === file_put_contents($filepath, $icon)) {
	http_response_code(500);
	die("Misconfigured server - could not write file.");
}

header("Content-Type: image/x-icon");
die($icon);

function getIcons() {
	return array(
		"youtube.com" => "http://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico",
		"wikipedia.org" => "http://en.wikipedia.org/favicon.ico",
		"imdb.com" => "http://i.media-imdb.com/images/desktop-favicon.ico",
		"fanfiction.net" => "http://www.fanfiction.net/static/images/favicon_2010_site.ico",
		"furry.org.au%2Fchakat" => "http://furry.org.au/chakat/favicon.ico",
		"stackoverflow.com" => "http://cdn.sstatic.net/stackoverflow/img/favicon.ico",
		"hulu.com" => "http://www.hulu.com/fat-favicon.ico",
		"mormon.org" => "http://edge.mormoncdn.org/favicon.ico",
		"lesswrong.com" => "http://lesswrong.com/static/favicon.ico",
		"gog.com" => "http://static.gog.com/favicon.ico",
		"steamcommunity.com" => "http://steamcommunity.com/favicon.ico",
		"skylords.com" => "http://skylords.com/favicon.ico",
		"minecraft.net" => "http://minecraft.net/favicon.png",
		"deviantart.com" => "http://i.deviantart.net/icons/favicon.png",
		"sofurry.com" => "http://www.sofurry.com/favicon.ico",
		"pinboard.in" => "https://pinboard.in/favicon.ico",
		"news.ycombinator.com" => "https://news.ycombinator.com/favicon.ico",
		"linkedin.com" => "http://linkedin.com/favicon.ico",
		"facebook.com" => "http://fbstatic-a.akamaihd.net/rsrc.php/yP/r/Ivn-CVe5TGK.ico",
		"plus.google.com" => "http://ssl.gstatic.com/s2/oz/images/faviconr3.ico",
		"pof.com" => "http://www.pof.com/favicon.ico",
		"github.com" => "https://github.com/favicon.ico",
		"gate.eveonline.com" => "https://gate.eveonline.com/eve_favicon.ico",
		"www.eveonline.com" => "http://www.eveonline.com/favicon.ico",
		"community.eveonline.com" => "http://community.eveonline.com/favicon.ico",
		"creativecommons.org" => "http://creativecommons.org/favicon.ico",
		"sourceforge.net" => "http://sourceforge.net/favicon.ico",
		"indeed.com" => "http://www.indeed.com/favicon.ico",
		"catb.org" => "http://catb.org/favicon.ico",
		"wikifur.com" => "http://en.wikifur.com/favicon.ico",
		"starwars.wikia.com" => "http://images4.wikia.nocookie.net/__cb62176/starwars/images/6/64/Favicon.ico",
		"gaiaonline.com" => "http://gaiaonline.com/favicon.ico",
		"ogame.org" => "http://ogame.org/favicon.ico",
		"simunomics.com" => "http://simunomics.com/favicon.ico",
		"bright-shadows.net" => "http://bright-shadows.net/favicon.ico",
		"istaria.com" => "http://istaria.com/favicon.ico",
		"neopets.com" => "http://www.neopets.com/favicon.ico",
		"entropiauniverse.com" => "http://www.entropiauniverse.com/favicon.ico",
		"secondlife.com" => "http://secondlife.com/favicon.ico",
		"hacker-project.com" => "http://hacker-project.com/favicon.ico",
		"planetside2.com" => "http://planetside2.com/favicon.ico",
		"ageofconan.com" => "http://l3cdn.funcom.com/aoc/uploads/favicon.ico",
		"guildwars.com" => "http://guildwars.com/favicon.ico",
		"nethack.org" => "http://nethack.org/favicon.ico",
		"mormon.org" => "http://edge.mormoncdn.org/favicon.ico",
		"welovefine.com" => "http://default-mightyfine.netdna-ssl.com/img/favicon.ico?1414385908",
	);
}
