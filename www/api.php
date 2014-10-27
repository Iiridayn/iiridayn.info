<?php
/**
 * Route API requests - flat heirachy
 */
$path = explode("/", ltrim($_SERVER['PATH_INFO'], "/"));
if (!empty($path[0]) && file_exists("api/".$path[0].".php"))
	require("api/".$path[0].".php");
