<?php require_once(dirname(__DIR__).'/db.inc.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Iiridayn's Video Games</title>
		<link rel="stylesheet" type="text/css" media="all" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" media="all" href="/css/style.css" />
		<link rel="stylesheet" type="text/css" media="all" href="/css/icons.css" />
	</head>
	<body>
		<h1>Video Games</h1>
		<p>Bold text means that I've beat the game. Grouped by systems, somewhat chronological (someday).</p>

		<!-- gog, steam, media; cleared, defeated, cheated? -->
		<!-- TODO: drop database - the website _is_ my database. -->
		<!-- Should have start/end markers for each section, strreplace the inside -->
	<?php
		$sql = <<<SQL
			SELECT name, gametime, notes, system, status, url
			FROM games
			ORDER BY system, name
SQL;
		$system_games = array();
		foreach ($db->query($sql) as $game) {
			$system_games[$game['system']] []= $game;
		}

		// localization
		$systems = array(
			'steam' => 'Steam',
			'gog' => 'GOG.com',
			'humble' => 'Humble Bundle (many from here to Steam)',
		);
	?>
	<?php foreach ($system_games as $system => $games): ?>
		<h2>Via <?= $systems[$system] ?></h2>
		<ul class="<?= $system ?>">
		<?php foreach ($games as $game): ?>
			<li class="<?= $game['status'].' '.$game['owner'] ?>">
			<?php if ($game['url']): ?>
				<a href="<?= $game['url'] ?>"><?= $game['name'] ?></a>
			<?php else: ?>
				<?= $game['name'] ?>
			<?php endif; ?>
			<?php if (!is_null($game['gametime'])): ?>
				(<?= $game['gametime'] ?> hours)
			<?php endif; ?>
			<?php if ($game['notes']): ?>
				- <?= $game['notes'] ?>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>

		<!-- todo - real games? -->
		<h2>Browser Games</h2>
		<ul>
			<li><a href="http://www.skylords.com/">SkyLords</a></li>
			<li><a href="http://www.neopets.com/">NeoPets</a></li>
			<li><a href="http://www.gaiaonline.com/">Gaia Online</a></li>
			<li><a href="http://www.hacker-project.com/">Hacker Project</a></li>
			<li><a href="http://simunomics.com/">Simunomics</a></li>
			<li><a href="http://ogame.org/">OGame</a></li>
			<li>Merchant Empires (formerly at advancedpowers.com)</li>
			<li>TryOrDie</li>
			<li><a href="http://www.nationstates.net/">NationStates</a></li>
		</ul>

		<h2>MMO with client software</h2>
		<ul>
			<li><a href="http://www.istaria.com/">Istaria</a></li>
			<li><a href="http://www.eveonline.com/">EVE Online</a></li>
			<li><a href="http://www.entropiauniverse.com/">Project Entropia</a></li>
			<li><a href="http://secondlife.com/">Second Life</a></li>
			<li><a href="http://www.ageofconan.com/">Age of Conan</a></li>
			<li>PlanetSide</li>
			<li><a href="https://www.planetside2.com/">PlanetSide 2</a></li>
			<li><a href="http://www.guildwars.com/">Guild Wars</a></li>
			<li><a href="http://gunz.aeriagames.com/">GunZ</a></li>
			<li><a href="http://muonline.webzen.com/main">MU online</a></li>
		</ul>

		<h2>MUDs</h2>
		<ul>
			<li>Shades of Evil (Ack!Mud)</li>
			<li>Glimmers of the Pattern</li>
			<li>Mists of Time<!--motmud.ath.cx--></li>
		</ul>

		<h2>Flash games</h2>
		<ul>
			<li><a href="http://games.adultswim.com/robot-unicorn-attack-twitchy-online-game.html">Robot Unicorn Attack</a></li>
			<li><a href="http://www.pages.drexel.edu/~mmj29/DIGM%20265/DragonRun.html">Dragon Run!</a></li>
		</ul>

		<h2>Computer - CD</h2>
		<ul>
			<li>Warcraft II</li>
			<li class="cleared">Fallout</li>
			<li>Fallout 2 (unplayed)</li>
			<li>Planescape: Torment - shortly before entering mortuary for last time</li>
			<li>Command &amp; Conquer + Covert Operations</li>
			<li>Gorasul - bug in first area prevented playthrough</li>
			<li>Darkened Skye</li>
			<li>I of the Dragon - somewhat boring gameplay</li>
			<li>Command &amp; Conquer: Renegade</li>
			<li>Red Alert 2 + Yuri's Revenge</li>
			<li>Command &amp; Conquer: Tiberian Sun + Firestorm</li>
			<li>Dungeon Siege</li>
			<li>Total Annihilation</li>
			<li class="defeated">Neverwinter Nights</li>
			<li class="defeated">Neverwinter Nights: Shadows of Undrentide</li>
			<li>Neverwinter Nights: Hordes of the Underdark</li>
			<li>Microsoft Fury3</li>
			<li class="cleared">Baldur's Gate</li>
			<li class="defeated">Baldur's Gate II + Throne of Bhaal</li>
			<li class="cleared">Final Fantasy VII</li>
			<li>Command &amp; Conquer: Red Alert + Counterstrike + The Aftermath</li>
			<li>Magic and Mayhem</li>
			<li>Oni</li>
			<li>Starcraft</li>
			<li class="defeated">Warcraft III</li>
			<li>Warcraft III: The Frozen Throne</li>
			<li class="defeated">Star Wars Jedi Knight II: Jedi Outcast</li>
			<li class="defeated">Homeworld</li>
			<li>Homeworld: Cataclysm</li>
			<li>The Elder Scrolls: Morrowind</li>
			<li class="cleared">The Elder Scrolls: Oblivion (GOTY)</li>
			<li>Final Fantasy VIII - at final dungeon (think I loaned these to a friend)</li>
			<li>Krush Kill 'N Destroy</li>
		</ul>

		<h2>Computer</h2>
		<ul>
			<li>The Elder Scrolls: Daggerfall</li>
			<li class="defeated"><a href="http://www.nethack.org/">Nethack</a></li>
			<li class="cleared">Cave Story</li>
			<li class="cleared"><a href="http://www.remar.se/daniel/iji.php">Iji</a></li>
			<li><a href="http://minecraft.net/">Minecraft</a></li>
			<!-- TODO: Oregon trail @ labs, scorched earth, scorched 3d, whole pile of save files -->
		</ul>

		<h2>NES</h2>
		<ul>
			<li>Super Mario Brothers</li>
			<li>Duck Hunt</li>
			<li class="defeated">Gauntlet II - past level 100</li>
			<li class="defeated"><a href="http://www.wisdomtreegames.com/games/spiritualwarfare/">Spiritual Warfare</a></li>
			<li>Exodus</li>
			<li>King of Kings</li>
			<li>Vindicators</li>
			<li>Xenophobia</li>
			<li>Iron Tank</li>
			<li>Sky Shark</li>
		</ul>

		<h2>Gameboy</h2>
		<ul>
			<!-- that spaceship game with the final boss being those eyedroppers with massive bullets -->
			<li>Tetris</li>
			<li>Qix</li>
			<li>The Legend of Zelda: Link's Awakening - save game corrupted outside final boss</li>
			<li>Pokemon Red (borrowed)</li>
		</ul>

		<h2>N64</h2>
		<ul>
			<li class="defeated">The Legend of Zelda: Orcarina of Time</li>
			<li class="cleared">Star Fox 64</li>
			<li class="cleared">Gauntlet Legends</li>
		</ul>

		<h2>SNES (mostly emulated)</h2>
		<ul>
			<li>Final Fantasy VI (III to some people) - at final dungeon</li>
			<li class="defeated">Megaman X</li>
			<li class="defeated">Megaman X3</li>
			<li class="defeated">Chrono Trigger</li>
			<li>Metal Warriors (friend's, 2 player only)</li>
			<li class="rental">Yoshi's Island - few levels</li>
			<li class="share">Street Fighter 2 - probably Turbo edition? Played a few times, lost quite badly</li>
		</ul>

		<h2>Dreamcast (friend's)</h2>
		<ul>
			<li>Power Stone</li>
		</ul>

		<h2>Play Station (emulated)</h2>
		<ul>
			<li>Final Fantasy IX - at start of final dungeon</li>
			<li class="defeated">Spyro the Dragon</li>
		</ul>

		<h2>Wii</h2>
		<ul>
			<li>Mario Kart Wii</li>
			<li>Mario Party 8</li>
			<li>Wii Sports</li>
			<li>Wii Play</li>
			<li class="defeated">New Super Mario Bros. Wii</li>
			<li class="cleared">Legend of Zelda: Twilight Princess</li>
			<li>Sonic and the Black Knight</li>
			<li>Super Mario Galaxy</li>
			<li>Super Smash Bros. Brawl</li>
			<li>Donkey Kong Country Returns</li>
			<li>Kirby's Epic Yarn</li>
			<li>Soulcalibur Legends</li>
			<li>Wii Play: Motion</li>
			<li>Spectrobes: Origins</li>
			<li>Rampage: Total Destruction</li>
			<li class="defeated">The Legend of Spyro: Dawn of the Dragon</li>
			<li class="defeated">The Legend of Spyro: The Eternal Night</li>
			<li>The Legend of Zelda: Skyward Sword</li>
			<li class="cleared">Metroid: Other M</li>
			<li class="defeated">Star Wars: The Force Unleashed</li>
			<li>Dragon's Lair Trilogy</li>
			<li>Ōkami</li>
		</ul>

		<h2>Gameboy Advanced (borrowed)</h2>
		<ul>
			<li>The Legend of Zelda: The Minish Cap</li>
			<li class="defeated">Metroid: Zero Mission</li>
			<li class="defeated">Metroid Fusion</li>
		</ul>

		<h2>Android</h2>
		<ul>
			<li>Angry Birds</li>
			<li>Simon Tantham's Puzzles</li>
		</ul>
	</body>
</html>
