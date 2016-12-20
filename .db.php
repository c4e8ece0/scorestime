<?php

ini_set('default_socket_timeout', 10);
date_default_timezone_set('Europe/Moscow');

$host = 'lenovo8';
$name = 'root';
$pass = 'zxcvbnm321';

$dblink = mysqli_connect($host, $name, $pass) or die(mysql_error());
mysqli_select_db($dblink, 'scorestime') or die(mysql_error());
mysqli_query($dblink, 'SET NAMES "utf8"') or die(mysql_error());

class DB
{
	function PathExists($path) {
		$moved = array(
			'/z-internal/all-geo/crimea/evpatoriya/' => '',
			'/z-internal/all-geo/crimea/feodosiya/' => '',
			'/z-internal/all-geo/crimea/sevastopol/' => '',
			'/z-internal/all-geo/usa/virgin-islands-of-the-united-states/' => '',
		);
		if(isset($moved[$path])) {
			return 'exists';
		}
		return  DB::Query('SELECT * FROM `fsh_wine_site` WHERE `path`=\''.addslashes($path).'\'');
	}

	static public function SetFormat($arr) {
		global $dblink;
		$r = '';
		foreach($arr as $k=>$v) {
			if(!strlen((string)$v) && ($k=='id' || strpos($k, '_id') !== false )) {
				unset($arr[$k]);
				continue;
			}
			if($r) {
				$r.=',';
			}
			$r.= '`'.mysqli_real_escape_string($dblink, $k).'`=';
			$r.= '\''.mysqli_real_escape_string($dblink, $v).'\'';
		}
		return $r;
	}

	static public function Query($q) {
		global $dblink;
		$result = mysqli_query($dblink, $q);
		if(!$result) {
			printf("SQL ERROR: %s [%s]", mysqli_error($dblink), $q);
			die();
		}

		if(($pos = strpos($q, ' ')) > 1) {
			$code = strtoupper(substr($q, 0, $pos));
		}

		switch($code) {
			// ------------------------------------------------------
			case 'REPLACE':
			case 'INSERT':
				return mysqli_insert_id($dblink);
			// ------------------------------------------------------
			case '(SELECT':
			case 'SELECT':
			case 'SHOW':
				$res = array();
				while ($row = mysqli_fetch_assoc($result)) {
					if(isset($row['id'])) {
						$res[$row['id']] = $row;
					} else {
						$res[] = $row;
					}
				}
				return $res;
			// ------------------------------------------------------
			case 'UPDATE':
			case 'DELETE':
				return mysqli_affected_rows($dblink);
			// ------------------------------------------------------
			default:
				return $result;
		}
	}
}

?>