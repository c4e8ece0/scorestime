<?php

class NewSoccerTable extends NewData
{
	public $_sport;
	public $_type;
	public $comp_id;
	public $season;
	public $sid;
}

class NewSoccerTableRow extends NewData
{
	public $_sport;
	public $_type;
	public $comp_id;
	public $n;
	public $club;
	public $p;
	public $w;
	public $d;
	public $l;
	public $fa1;
	public $fa2;
	public $pnt;
	public $lp;
	public $cid;
	public $sid;
}

function soccer_table($table) {
	$err = array();
	$t = new NewSoccerTable();
	$t->_sport = 'soccer';
	$t->_type = 'table';
	$t->comp_id = $table->attributes()->IDcomp;
	$t->season = $table->attributes()->season;
	$t->sid = $table->attributes()->sid;
	$p = $t->Publish();
	if($p) {
		$err[]=$p;
	}

	foreach($table->row as $row) {
		$t = new NewSoccerTableRow();
		$t->_sport = 'soccer';
		$t->_type = 'table_row';
		$t->comp_id = $table->attributes()->IDcomp;
		$t->n = $row->attributes()->n;
		$t->club = $row->attributes()->Club;
		$t->p = $row->attributes()->P;
		$t->w = $row->attributes()->W;
		$t->d = $row->attributes()->D;
		$t->l = $row->attributes()->L;
		$t->fa1 = $row->attributes()->FA1;
		$t->fa2 = $row->attributes()->FA2;
		$t->pnt = $row->attributes()->Pnt;
		$t->lp = $row->attributes()->LP;
		$t->cid = (int) $row->attributes()->cid;
		$t->sid = $table->attributes()->sid;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}
	}
	return $err;
}


?>