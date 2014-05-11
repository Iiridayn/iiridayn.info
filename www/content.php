<?php

function modified($time) {
	$date = gmdate("D, d M Y H:i:s", $time)." GMT";
	header("Last-Modified: ".$date);
	return $date;
}

function unslug($name) {
	return ucfirst(str_replace("_", " ", basename($name, ".html")));
}

$filename = basename($_SERVER['QUERY_STRING']);
$filepath = "../content/".$filename;

$content = '';
if ($filename && file_exists($filepath)) {
	$filetime = filemtime($filepath);
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $filetime == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		header("HTTP/1.1 304 Not Modified");
		exit();
	}
	$date = modified($filetime);

	$title = "Iiridayn wrote: ".unslug($filename);
	$content = file_get_contents($filepath);
} elseif ($filename) {
	http_response_code(404);
}

if (!$content) {
	$title = "Iiridayn's Writing Index";

	$files = array();
	// ... the unwieldy handles ALMOST make me prefer glob.
	$fs = new FilesystemIterator("../content/",
		FilesystemIterator::NEW_CURRENT_AND_KEY | FilesystemIterator::SKIP_DOTS);
	foreach ($fs as $filename => $fileinfo) {
		if ($fileinfo->getExtension() !== "html") continue;
		$files[$fileinfo->getMTime()] = $filename;
	}
	krsort($files);

	$content = <<<HTML
		<h1>Iiridayn's Writings</h1>
		<p>A simple listing of my writing. Newest up top.</p>
HTML;
	$content .= "<ol style=\"list-style-type: none\">";
	foreach ($files as $time => $filename) {
		$content .= "<li>".date("Y-m-d H:i:s", $time)." <a href=\"/content/$filename\">".unslug($filename)."</a></li>";
	}

	$content .= "</ol>";

	reset($files);
	$date = modified(max(filemtime(__FILE__), key($files)));
}

include("../content/template.php");
