<?php

$lines = explode("\n", trim(file_get_contents("sections.csv")));

$sections = [];
$json = [];
$curr = '';

foreach ($lines as $l) {
	$b = str_getcsv($l);

	$b[1] = substr($b[1], 0, -2);

	if (strlen($b[1]) == 0) {
		$curr = $b[0];
		$sections[$curr] = [];
		continue;
	} else {
		$sections[$curr][] = $b;
		$json[$b[0]] = $b[1];
	}
}


foreach ($sections as $area=>$s) {

	echo "<div>\n\t<h4>$area</h4>\n\t<ul>\n";
	foreach ($s as $e) {
		echo "\t\t<li><span>{$e[0]}</span> &mdash; {$e[1]}</li>\n";
	}
	echo "\t</ul>\n</div>\n";


}

echo "<script>\nvar sections = ";
echo json_encode($json, JSON_PRETTY_PRINT);
echo ";\n</script>\n";

