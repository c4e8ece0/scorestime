<?php

class NewCompetition extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $country_id;
	public $runame;
	public $enname;
	public $date;
}

function all_competitions($xml) {
	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = $comp->attributes()->sport;
		$t->_type = 'competition';
		$t->id = $comp->attributes()->ID;
		$t->enname = $comp->attributes()->name;
		$t->runame = $comp->attributes()->name_r2;
		$t->country_id = $comp->attributes()->countryID;
		$t->date = $comp->attributes()->date;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}
	}
	return $err;
}

?>