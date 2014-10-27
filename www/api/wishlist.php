<?php
/**
 * Not going to use a caching quine as it's difficult to source control.
 * REST API for storing which wishlist items are covered
 */
$file = "../var/wishlist.txt"; // running in context of www/api.php

$id = null;
if (!empty($path[1])) {
	$id = $path[1];
	$wishlist = file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
}

// TODO: DOS prevention; limit total file storage at least (1k?)
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if (!$id) {
			readfile($file);
			die();
		}
		if (!in_array($id, $wishlist)) {
			http_response_code(404);
		}
		die();
	case 'PUT':
		if (!$id) {
			http_response_code(403);
			die;
		}

		if (!in_array($id, $wishlist)) {
			if (false === file_put_contents($file, $id."\n", FILE_APPEND|LOCK_EX)) {
				http_response_code(500);
				die("An error occured saving your data");
			}
			http_response_code(201); // "Created"
		}
		die;
	case 'DELETE':
		if (!$id) {
			http_response_code(403);
			die;
		}

		if (false !== ($index = array_search($id, $wishlist))) {
			unset($wishlist[$index]);
			if (false === file_put_contents($file, implode("\n", $wishlist)."\n", LOCK_EX)) {
				http_response_code(500);
				die("An error occured saving your data");
			}
			http_response_code(204);
		} else {
			http_response_code(404);
		}
		die;
	default:
		http_response_code(405);
		die("The wishlist service only supports GET/PUT/DELETE methods.");
}
