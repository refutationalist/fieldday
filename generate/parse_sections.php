<?php

$lines = explode("\n", trim(file_get_contents("sections.csv")));

$sections = [];
$curr = '';

foreach ($lines as $l) {
	$b = str_getcsv($l);
	//print_r($b);continue;
	if (strlen($b[1]) == 0) {
		$curr = $b[0];
		$sections[$curr] = [];
		continue;
	} else {
		$sections[$curr][] = $b;
	}
}


foreach ($sections as $area=>$s) {

	echo "<div>\n\t<h4>$area</h4>\n\t<ul>\n";
	foreach ($s as $e) {
		echo "\t\t<li><span>{$e[0]}</span> &mdash; {$e[1]}</li>\n";
	}
	echo "\t</ul>\n</div>\n";


}
