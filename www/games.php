<?php require_once(dirname(__DIR__).'/db.inc.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title>Iiridayn's Video Games</title>
	</head>
	<body>
		<h1>Video Games</h1>
		<p>Strong text means that I've beat the game. Grouped by systems, somewhat chronological.</p>

		<!-- gog, steam, media; cleared, defeated, cheated? -->
	<!-- TODO: drop database - the website _is_ my database. -->
	<!-- tricky, but I believe that I can work something out -->
	<?php
	$sql = <<<SQL
		SELECT name, gametime, notes, system, status, owner, url
		FROM games
		ORDER BY system, name
SQL;
	$system_games = array();
	foreach ($db->query($sql) as $game) {
		$system_games[$game['system']] []= $game;
	}

	// localization
	$systems = array(
		'pc' => 'Computer',
		'nes' => 'NES',
		'gameboy' => 'Gameboy',
		'n64' => 'N64',
		'snes' => 'SNES',
		'playstation' => 'Play Station',
		'xbox' => 'Xbox',
		'wii' => 'Wii',
	);
	?>
	<?php foreach ($system_games as $system => $games): ?>
		<h2><?= $systems[$system] ?></h2>
		<ul class="<?= $system ?>">
		<?php foreach ($games as $game): ?>
			<li class="<?= $game['status'].' '.$game['owner'] ?>">
				<?php if ($game['url']): ?>
				<a href="<?= $game['url'] ?>">
				<?php endif; ?>
				<?= $game['name'] ?>
				<?php if ($game['url']): ?>
				</a>
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
		<h2>Internet</h2>
		<ul>
			<li>NeoPets</li>
			<li>Gaia Online</li>
			<li>Project Entropia</li>
			<li>Second Life</li>
			<li>Merchant Empires</li>
			<li>SkyLords</li>
			<li>TryOrDie</li>
			<li>EVE Online</li>
		</ul>

		<h2>Computer</h2>
		<ul>
			<li>Warcraft II</li>
			<li>Command and Conquer</li>
			<li>Starcraft</li>
			<li class="defeated">Warcraft III</li>
			<li>The Elder Scrolls: Morrowind</li>
			<li class="cleared">The Elder Scrolls: Oblivion</li>
			<li>The Elder Scrolls: Skyrim</li>
			<li>The Elder Scrolls: Daggerfall</li>
			<li class="defeated">Nethack</li>
			<li class="cleared">Cave Story</li>
			<li class="defeated">Final Fantasy VII</li>
			<li>Final Fantasy VIII - at final dungeon</li>
		</ul>

		<h2>NES</h2>
		<ul>
			<li>Super Mario Brothers</li>
			<li>Duck Hunt</li>
			<li class="defeated">Gauntlet II - past level 100</li>
			<li class="defeated">Spiritual Warfare</li>
			<li>King of Kings</li>
		</ul>

		<h2>Gameboy</h2>
		<ul>
			<li>Tetris</li>
			<li>Qix</li>
			<li>The Legend of Zelda: Link's Awakening - lost game at final boss</li>
		</ul>

		<h2>N64</h2>
		<ul>
			<li class="defeated">The Legend of Zelda: Orcarina of Time</li>
		</ul>

		<h2>SNES (emulated)</h2>
		<ul>
			<li>Final Fantasy VI (III to some people) - at final dungeon</li>
			<li class="defeated">Megaman X</li>
			<li class="defeated">Megaman X3</li>
		</ul>

		<h2>Play Station (emulated)</h2>
		<ul>
			<li>Final Fantasy IX - at start of final dungeon</li>
		</ul>

		<h2>Wii</h2>
		<ul>
			<li>Mario Kart</li>
			<li>Mario Party 8</li>
		</ul>

		<h2>Android</h2>
		<ul>
			<li>Angry Birds</li>
			<li>Simon Tantham's Puzzles</li>
		</ul>
	</body>
</html>
