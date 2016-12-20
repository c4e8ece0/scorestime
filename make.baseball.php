<?php

class NewBaseballStatus extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $status;
	public $p1_1;
	public $p1_2;
	public $p2_1;
	public $p2_2;
	public $p3_1;
	public $p3_2;
	public $p4_1;
	public $p4_2;
	public $p5_1;
	public $p5_2;
	public $p6_1;
	public $p6_2;
	public $p7_1;
	public $p7_2;
	public $p8_1;
	public $p8_2;
	public $p9_1;
	public $p9_2;
	public $r1;
	public $r2;
	public $h_1;
	public $h_2;
	public $e_1;
	public $e_2;
}


//
function baseball_fixtures($xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = 'baseball';
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
			$t->_sport = 'baseball';
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
			$t->_sport = 'baseball';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid1;
			$t->runame = $v->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'baseball';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid2;
			$t->runame = $v->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($v->status_result) {
				$t = new NewBaseballStatus();
				$t->_sport = 'baseball';
				$t->_type = 'match_status';
				$t->match_id = $v->attributes()->id;
				$t->status = $v->status_result->attributes()->status;
				$t->p1_1 = $v->status_result->attributes()->p1_1;
				$t->p1_2 = $v->status_result->attributes()->p1_2;
				$t->p2_1 = $v->status_result->attributes()->p2_1;
				$t->p2_2 = $v->status_result->attributes()->p2_2;
				$t->p3_1 = $v->status_result->attributes()->p3_1;
				$t->p3_2 = $v->status_result->attributes()->p3_2;
				$t->p4_1 = $v->status_result->attributes()->p4_1;
				$t->p4_2 = $v->status_result->attributes()->p4_2;
				$t->p5_1 = $v->status_result->attributes()->p5_1;
				$t->p5_2 = $v->status_result->attributes()->p5_2;
				$t->p6_1 = $v->status_result->attributes()->p6_1;
				$t->p6_2 = $v->status_result->attributes()->p6_2;
				$t->p7_1 = $v->status_result->attributes()->p7_1;
				$t->p7_2 = $v->status_result->attributes()->p7_2;
				$t->p8_1 = $v->status_result->attributes()->p8_1;
				$t->p8_2 = $v->status_result->attributes()->p8_2;
				$t->p9_1 = $v->status_result->attributes()->p9_1;
				$t->p9_2 = $v->status_result->attributes()->p9_2;
				$t->r1 = $v->status_result->attributes()->r1;
				$t->r2 = $v->status_result->attributes()->r2;
				$t->h_1 = $v->status_result->attributes()->h_1;
				$t->h_2 = $v->status_result->attributes()->h_2;
				$t->e_1 = $v->status_result->attributes()->e_1;
				$t->e_2 = $v->status_result->attributes()->e_2;
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