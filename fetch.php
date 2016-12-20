<?php

include_once '.db.php';

ini_set('pcre.backtrack_limit', 100 * 1024 * 1024);
ini_set('default_socket_timeout', 30);

$_cnt = 1;
while(1) {
	printf("#############################\n");
	printf("Cycle N %d\n", $_cnt++);
	printf("#############################\n");

	// Проход по всем видам спорта
	$CPs = array(); // надо отработать отдельно для футбола <cp id="814">
	printf("Fetching sports\n");
	printf("-----------------------------\n");
	foreach(listSport() as $_sport) {
		printf("%s: ", $_sport);

		$t = getFor($_sport, "");
		printf("%s(%d) ", "", strlen($t));
		printf("done\n");
		if($_sport == 'soccer') {
			preg_match_all('#<cp id="([^"]*)"#isu', $t, $m, PREG_SET_ORDER);
			foreach($m as $k=>$v) {
				$CPs[] = $v[1];
			}
		}

		$t = getFor($_sport, "fixtures");
		printf("%s(%d) ", "fixtures", strlen($t));
		printf("done\n");
		if($_sport == 'soccer') {
			preg_match_all('#<cp id="([^"]*)"#isu', $t, $m, PREG_SET_ORDER);
			foreach($m as $k=>$v) {
				$CPs[] = $v[1];
			}
		}
	}
	printf("-----------------------------\n");

	//
	printf("Fetching tables (soccer only)\n");
	printf("-----------------------------\n");
	$CPs = array_unique($CPs);
	printf("CPs num: %d\n", count($CPs));
	if(count($CPs)) {
		printf("Tables: ");
			foreach($CPs as $cpid) {
				$t = getFor('soccer', "table", $cpid);
				printf("cp=%d(%s) ", $cpid, strlen($t));
			}
		printf("\n");
	}
	printf("-----------------------------\n");

	//
	printf("Fetching players\n");
	$t = getFor("", "test");
	printf("%d\n", strlen($t));
	printf("-----------------------------\n");

	//
	printf("Competitions\n");
	$t = getFor('', "competitions");
	printf("%d\n", strlen($t));
	printf("-----------------------------\n");

	include 'move2db.php';
	// include 'make.php';
	sleep(180);
	printf("\n\n\n");
}

function listSport(){
	// return array('soccer');
	return array(
		'soccer', 
		'hockey', 
		'basketball', 
		'baseball', 
		'football', 
		'handball', 
		'volleyball', 
		'rugby', 
		'cricket', 
		'rugbyl', 
		'futsal', 
		'beachfootball', 
		'waterpolo', 
		'tennis', 
		'basketball', 
		'formula1', 
		'golf', 
	);
}

function listTypes() {
	return array(
		'',
		'fixtures',
		'competitions', // полный список без вида спорта
		'table', // + id=[D] есть только для соккера
		'test', // пока список 
	);
}

function getFor($sport = '', $type = '', $id = 0) {
	sleep(1);
	$par = array();
	if($sport) {
		$par['sport'] = $sport;
	}
	if($type) {
		$par['type'] = $type;
	}
	if($id) {
		$par['id'] = $id;
	}
	$query = http_build_query($par);
	$dir = dirname(__FILE__) .strftime('/arc-%Y-%m-%d/');
	$topath = $dir . str_replace(array('&'), '__', $query).strftime(".%Y-%m-%d-%H-%M-%S").'.xml';
	if(!file_exists($dir)) {
		mkdir($dir, 0777, true);
	}

	$url = '...?'.$query;
	$data = @file_get_contents($url);
	if(!$data && strpos(@$http_response_header[0], ' 200 ') == false) {
		print 'FETCHERROR:';
		print_r($http_response_header) ;
		return '';
	}

	if(strpos($data, 'encoding="windows-1251"') !== false) {
		$data = iconv('Windows-1251', 'UTF-8', $data);
		$data = str_replace('encoding="windows-1251"?>', 'encoding="utf-8"?>', $data);
	}

	file_put_contents($topath, $data);
	InsertData($topath);
	return $data;
}

function InsertData($path) {
	global $dblink;
	$chk = md5_file($path);
	$filename = basename($path);

	printf("%s\t", $filename);
	if(DB::Query("SELECT 1 FROM `xml` WHERE `chk`='$chk' LIMIT 1")) {
		print "X $chk\n";
		// rename($path, $_dst.$filename);
		return;
	}

	$item = array(
		'name' => $filename,
		'data' => file_get_contents($path),
		'chk' => $chk,
	);

	$iii = array();
	foreach($item as $a=>$b) {
		$iii[] = "`$a`='".mysqli_real_escape_string($dblink, $b)."'";
	}
	DB::Query("INSERT INTO `xml` SET ".implode(',', $iii));
	if(mysqli_error($dblink)) {
		print mysqli_error($dblink);
		exit(1);
	}
	// rename($path, $_dst.$filename);
	print "V\n";
}


?>