<?php

class NewRugbyStatus extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $status;
	public $rh1;
	public $rh2;
	public $r1;
	public $r2;
}


//
function rugby_fixtures($xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = 'rugby';
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
			$t->_sport = 'rugby';
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
			$t->_sport = 'rugby';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid1;
			$t->runame = $v->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'rugby';
			$t->_type = 'club';
			$t->id = $v->attributes()->cid2;
			$t->runame = $v->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($v->status_result) {
				$t = new NewRugbyStatus();
				$t->_sport = 'rugby';
				$t->_type = 'match_status';
				$t->match_id = $v->attributes()->id;
				$t->status = $v->status_result->attributes()->status;
				$t->rh1 = $v->status_result->attributes()->rH1;
				$t->rh2 = $v->status_result->attributes()->rH2;
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