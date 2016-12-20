<?php

class NewData
{
	public function Publish() {
		global $dblink;

		$arr = json_decode(json_encode((array) $this), TRUE);
		foreach($arr as $k=>$v) {
			if(is_array($v)) {
				$arr[$k] = current((array) $v);
			}
			if($k == 'id' || $k == 'sid' || $k == 'cid' || strpos($k, '_id')) {
				$arr[$k] = (int) $arr[$k];
				if($arr[$k] < 0) {
					$arr[$k] = 0;
				}
			}
		}

		$cur = array();
		if(isset($arr['id'])) {
			$cur = DB::Query(sprintf("SELECT * FROM `%s_%s` WHERE `id`='%d' LIMIT 1",
				mysqli_real_escape_string($dblink, $arr['_sport']),
				mysqli_real_escape_string($dblink, $arr['_type']),
				mysqli_real_escape_string($dblink, $arr['id'])
			));
		}

		// update query
		if($cur) {
			$aaa = array_pop($cur);
			$upd = array();
			foreach($aaa as $k=>$v) {
				if($k == 'ts') {
					continue;
				}
				if(isset($arr[$k])) {
					if($v != $arr[$k]) {
						$upd[$k] = $aaa[$k];
					}
				} elseif(@$arr[$k]) {
					printf("%s(%d): UNKNOWN_FIELD[%s|%s|%s](%s != %s)\n",
						__FILE__,
						__LINE__,
						$k,
						$arr[$k],
						$aaa[$k],
						implode('/', array_keys($arr)),
						implode('/', array_keys($aaa))
					);
					die();
				}
			}
			$ustr = trim(DB::SetFormat($upd));
			if($upd && $ustr) {
				print 'UPDATE ';
				DB::Query(sprintf("UPDATE `%s_%s` SET %s WHERE `id`='%d'",
					$arr['_sport'],
					$arr['_type'],
					$ustr,
					$arr['id']
				));
			}
		}
		// insert query
		else {
			$new = $arr;
			unset($new['_sport'], $new['_type']);
			DB::Query(sprintf('REPLACE INTO `%s_%s` SET %s',
				$arr['_sport'],
				$arr['_type'],
				DB::SetFormat($new)
			));
		}

		if(mysqli_error($dblink)) {
			print_r($arr);
		}

		return mysqli_error($dblink);
	}
}

?>