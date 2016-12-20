<?php

class NewMatch extends NewData
{
	public $_sport;
	public $_type;
	public $comp_id;
	public $id;
	public $date;
	public $time;
	public $club_id1;
	public $club_id2;
}

class NewClub extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $runame;
	public $enname;
}

//
function common_fixtures($sportname, $xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = $sportname;
		$t->_type = 'competition';
		$t->id = $comp->attributes()->id;
		$t->runame = $comp->attributes()->name;
		$t->date = $comp->attributes()->date;
		$p = $t->Publish();
		// print_r(array(__FUNCTION__,$t));
		if($p) {
			$err[]=$p;
		}

		foreach($comp->match as $k=>$v) {
			$t = new NewMatch();
			$t->_sport = $sportname;
			$t->_type = 'match';
			$t->date = $comp->attributes()->date;
			$t->id = $v->attributes()->id;
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
			$t->_sport = $sportname;
			$t->_type = 'club';
			$t->id = $v->attributes()->cid1;
			$t->runame = $v->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = $sportname;
			$t->_type = 'club';
			$t->id = $v->attributes()->cid2;
			$t->runame = $v->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}
		}
	}

	return $err;
}

?>