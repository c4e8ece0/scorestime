<?php

// --------------------------------------------------------------------------
// Сбор структуры xml-документа
// --------------------------------------------------------------------------
ini_set('pcre.backtrack_limit', 1024 * 1024 * 1024);
include '.db.php';
gc_enable();

$was = array();
$slugcnt = array();
$stat = array();
$new = array();

$skip = 0;

$result = mysqli_query($dblink, "SELECT * FROM `xml` order by `name`", MYSQLI_USE_RESULT);
while($t = mysqli_fetch_assoc($result)) {
	printf("CUR=%d\n", $t['id']);
	
	$filename = $t['name'];
	$mdf = $t['chk'];
	$xml = $t['data'];

	list($pref) = explode('.', $filename);
	$pref = preg_replace('#[0-9]+#is', '', $pref);

	@$slugcnt[$pref]['found']++;
	if(isset($was[$mdf])) {
		print '~';
		@$slugcnt[$pref]['new']++;
		continue;
	}
	$was[$mdf] = '';
	// $new[] = $filename;

	printf("\n%s\n", $filename);

	$err = 0;
	while(1) {
		if(strpos($xml, '<?xml version="1.0" encoding="utf-8"?>')) {
			break;
		}
		if($err > 1) {
			continue 2;
		}
		if(!preg_match_all('#<(([a-zA-Z0-9\-_\.:]*)[^>]*)>#isu', $xml, $f, PREG_SET_ORDER)) {
			$xml = iconv('Windows-1251', 'UTF-8', $xml);
			print "#";
			$err++;
		} else {
			break;
		}
#		print_r($f);
#		$err = 2;
	}

	$n = 0;
	for($i=0; $i<count($f); $i++) {
		$v = &$f[$i];
		print '.';
		if($v[1][0] == '/') { // skip closing tags
			continue;
		}
		@$stat[$pref][$v[2]]['cnt']++;

		if(!preg_match_all('#([a-z0-9\-_\.:]+)=#isu', $v[1], $m, PREG_SET_ORDER)) {
			// print_r(array('EMPTY', $v));
			continue;
		}
		foreach($m as &$b) {
			@$stat[$pref][ $v[2]] ['tag'][ $b[1] ]++; // 'attr' of couse
		}
	}
	print "\n";
	gc_collect_cycles();
}

ob_start();
print_r($stat);
file_put_contents('-lookup.txt', ob_get_clean());

ob_start();
print_r($slugcnt);
file_put_contents('-slugcnt.txt', ob_get_clean());

// ob_start();
// print_r($new);
// file_put_contents('-newdatafile.txt', ob_get_clean());

?>