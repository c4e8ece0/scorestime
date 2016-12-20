<?php

class NewVolleyballStatus extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $status;
	public $s1_1;
	public $s1_2;
	public $s2_1;
	public $s2_2;
	public $s3_1;
	public $s3_2;
	public $s4_1;
	public $s4_2;
	public $s5_1;
	public $s5_2;
	public $r1;
	public $r2;
}


//
function volleyball_fixtures($xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = 'volleyball';
		$t->_type = 'competition';
		$t->id = $comp->attributes()->id;
		$t->runame = $comp->attributes()->name;
		$t->date = $comp->attributes()->date;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}

		foreach($comp->match as $k=>$v) {
			$t = new NewMatch();
			$t->_sport = 'volleyball';
			$t->_type = 'match';
			$t->id = $v->attributes()->id;
			$t->date = $comp->attributes()->date;
			$t->comp_id = $comp->attributes()->id;
			$t->time = $v->attributes()->time;
			$t->club_id1 = $v->attributes()->cid1;
			$t->club_id2 = $v->attributes()->cid2;
			$t->club1 = $v->attributes()->club1;
			$t->club2 = $v->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'volleyball';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid1;
			$t->runame = $v->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'volleyball';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid2;
			$t->runame = $v->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($v->status_result) {
				$t = new NewVolleyballStatus();
				$t->_sport = 'volleyball';
				$t->_type = 'match_status';
				$t->match_id = $v->attributes()->id;
				$t->status = $v->status_result->attributes()->status;
				$t->s1_1 = $v->status_result->attributes()->s1_1;
				$t->s1_2 = $v->status_result->attributes()->s1_2;
				$t->s2_1 = $v->status_result->attributes()->s2_1;
				$t->s2_2 = $v->status_result->attributes()->s2_2;
				$t->s3_1 = $v->status_result->attributes()->s3_1;
				$t->s3_2 = $v->status_result->attributes()->s3_2;
				$t->s4_1 = $v->status_result->attributes()->s4_1;
				$t->s4_2 = $v->status_result->attributes()->s4_2;
				$t->s5_1 = $v->status_result->attributes()->s5_1;
				$t->s5_2 = $v->status_result->attributes()->s5_2;
				$t->r1 = $v->status_result->attributes()->r1;
				$t->r2 = $v->status_result->attributes()->r2;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}
		}
	}

	return $err;
}

?>