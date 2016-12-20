<?php
include_once '.db.php';
date_default_timezone_set('Europe/Moscow');
error_reporting(E_ALL);
// --------------------------------------------------------------------------
// Перевод XML-данных в БД
// --------------------------------------------------------------------------
include_once 'make.data.php';
include_once 'make.base.php';
include_once 'make.competition.php';
include_once 'make.common.php';

include_once 'make.baseball.php';
include_once 'make.handball.php';
include_once 'make.hockey.php';
include_once 'make.volleyball.php';
include_once 'make.rugby.php';

if(!function_exists('beachfootball_fixtures')) {
	function cricket_fixtures($xml) {return common_fixtures('cricket', $xml);}
	function futsal_fixtures($xml) {return common_fixtures('futsal', $xml);}
	function rugbyl_fixtures($xml) {return common_fixtures('rugbyl', $xml);}
	function waterpolo_fixtures($xml) {return common_fixtures('waterpolo', $xml);}
	function beachfootball_fixtures($xml) {return common_fixtures('beachfootball', $xml);}
}
include_once 'make.baseball.php';
include_once 'make.basketball.php';
include_once 'make.football.php';
include_once 'make.tennis.php';
include_once 'make.soccer.php';
include_once 'make.soccer_table.php';
// --------------------------------------------------------------------------

// DB::Query("UPDATE `xml` SET `done`=0");

while(1) {
	print "Getting one..";
	$pre = DB::Query("SELECT * FROM `xml` WHERE `done`=0 AND `name` NOT LIKE '%type=test%'  ORDER BY `name` ASC LIMIT 1");
	// $pre = DB::Query("SELECT * FROM `xml` WHERE `done`=0 AND `name` NOT LIKE '%type=test%' AND `name` NOT LIKE '%hockey%' ORDER BY `name` ASC LIMIT 1");
	// $pre = DB::Query("SELECT * FROM `xml` WHERE `done`=0 AND `name` LIKE '%competition%' LIMIT 1");
	if(mysqli_error($dblink)) {
		die(mysqli_error($dblink));
	}
	print ".\n";
	$arr = array_pop($pre);
	printf("%s\n", $arr['name']);
	$r = onejob($arr['name'], $arr['data']);
	if(!$r) {
		if(!$arr['id']) {
			printf('Unknown id');
			break;
		}
		DB::Query("UPDATE `xml` SET `done`=1 WHERE `id`=".$arr['id']);
		print "OK\n";
	} else {
		print_r($r);
	}
	// die('die young');
}

printf("Done!");

?>