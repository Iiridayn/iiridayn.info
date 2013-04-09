<?php
// Apache loads the same $_SERVER vars
foreach (file(__DIR__.'/environment.conf') as $line) {
	$conf = explode(' ', $line);
	$_SERVER[$conf[1]] = substr($conf[2], 1, strlen($conf[2])-3);
}
