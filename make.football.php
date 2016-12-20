<?php

class NewFootballStatus extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $status;
	public $q1_1;
	public $q1_2;
	public $q2_1;
	public $q2_2;
	public $q3_1;
	public $q3_2;
	public $q4_1;
	public $q4_2;
	public $ot_1;
	public $ot_2;
	public $r1;
	public $r2;
}

//
function football_fixtures($xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = 'football';
		$t->_type = 'competition';
		$t->id = $comp->attributes()->id;
		$t->runame = $comp->attributes()->name;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}

		foreach($comp->match as $k=>$match) {
			$t = new NewMatch();
			$t->_sport = 'football';
			$t->_type = 'match';
			$t->date = $comp->attributes()->date;
			$t->id = $match->attributes()->id;
			$t->comp_id = $comp->attributes()->id;
			$t->time = $match->attributes()->time;
			$t->club_id1 = $match->attributes()->cid1;
			$t->club_id2 = $match->attributes()->cid2;
			$t->club1 = $match->attributes()->club1;
			$t->club2 = $match->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'football';
			$t->_type = 'club';
			$t->id = $match->attributes()->cid1;
			$t->runame = $match->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'football';
			$t->_type = 'club';
			$t->id = $match->attributes()->cid2;
			$t->runame = $match->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($match->status_result) {
				$t = new NewFootballStatus();
				$t->_sport = 'football';
				$t->_type = 'match_status';
				$t->match_id = $match->attributes()->id;
				$t->status = $match->status_result->attributes()->status;
				$t->q1_1 = $match->status_result->attributes()->q1_1;
				$t->q1_2 = $match->status_result->attributes()->q1_2;
				$t->q2_1 = $match->status_result->attributes()->q2_1;
				$t->q2_2 = $match->status_result->attributes()->q2_2;
				$t->q3_1 = $match->status_result->attributes()->q3_1;
				$t->q3_2 = $match->status_result->attributes()->q3_2;
				$t->q4_1 = $match->status_result->attributes()->q4_1;
				$t->q4_2 = $match->status_result->attributes()->q4_2;
				$t->ot_1 = $match->status_result->attributes()->ot_1;
				$t->ot_2 = $match->status_result->attributes()->ot_2;
				$t->r1 = $match->status_result->attributes()->r1;
				$t->r2 = $match->status_result->attributes()->r2;
				$t->Publish();
			}
		}
	}

	return $err;
}

?>