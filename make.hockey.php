<?php

class NewHockeyStatus extends NewData
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
	public $ot_1;
	public $ot_2;
	public $pen_1;
	public $pen_2;
	public $r1;
	public $r2;

}

class NewHockeyEvent extends NewData
{
	public $_sport;
	public $_type;

	public $match_id;

	public $player_id;
	public $player_runame;

	public $assists2_id;
	public $assists2_runame;
	public $assists_id;
	public $assists_runame;

	public $event;
	public $minute;
	public $duration;
	public $club;
	public $idid;
}



class NewHockeyLineup extends NewData
{
	public $_sport;
	public $_type;
	// public $id;
	public $match_id; // ++ clubid
	public $cb;
	public $no;
	public $player_id;
	public $player_name;
	public $line;
	public $pos;
}

class NewHockeyPlayer extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $runame;
	public $enname;
}


//
function hockey_fixtures($xml) {

	$err = array();
	foreach($xml->competition as $comp) {
		$t = new NewCompetition();
		$t->_sport = 'hockey';
		$t->_type = 'competition';
		$t->id = $comp->attributes()->id;
		$t->runame = $comp->attributes()->name;
		$t->date = $comp->attributes()->date;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}

		foreach($comp->match as $match) {
			$t = new NewMatch();
			$t->_sport = 'hockey';
			$t->_type = 'match';
			$t->id = $match->attributes()->id;
			$t->date = $comp->attributes()->date;
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
			$t->_sport = 'hockey';
			$t->_type = 'club';
			$t->id = $match->attributes()->cid1;
			$t->runame = $match->attributes()->club1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewClub();
			$t->_sport = 'hockey';
			$t->_type = 'club';
			$t->id = $match->attributes()->cid2;
			$t->runame = $match->attributes()->club2;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($match->status_result) {
				$t = new NewHockeyStatus();
				$t->_sport = 'hockey';
				$t->_type = 'match_status';
				$t->match_id = $match->attributes()->id;
				$t->status = $match->status_result->attributes()->status;
				$t->p1_1 = $match->status_result->attributes()->p1_1;
				$t->p1_2 = $match->status_result->attributes()->p1_2;
				$t->p2_1 = $match->status_result->attributes()->p2_1;
				$t->p2_2 = $match->status_result->attributes()->p2_2;
				$t->p3_1 = $match->status_result->attributes()->p3_1;
				$t->p3_2 = $match->status_result->attributes()->p3_2;
				$t->ot_1 = $match->status_result->attributes()->ot_1;
				$t->ot_2 = $match->status_result->attributes()->ot_2;
				$t->pen_1 = $match->status_result->attributes()->pen_1;
				$t->pen_2 = $match->status_result->attributes()->pen_2;
				$t->r1 = $match->status_result->attributes()->r1;
				$t->r2 = $match->status_result->attributes()->r2;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($match->details as $event) {
				$t = new NewHockeyEvent();
				$t->_sport = 'hockey';
				$t->_type = 'event';
				$t->match_id = $match->attributes()->id;

				$id[0] = $t->player_id = $event->attributes()->IDplayer;

				$nm[0] = $t->player_runame = $event->attributes()->player;
				$id[1] = $t->assists_id = $event->attributes()->IDassists;
				$nm[1] = $t->assists_runame = $event->attributes()->assists;
				$id[2] = $t->assists2_id = $event->attributes()->IDassists2;
				$nm[2] = $t->assists2_runame = $event->attributes()->assists2;
				$t->event = $event->attributes()->event;
				$t->minute = $event->attributes()->minute;
				$t->duration = $event->attributes()->duration;
				$t->club = $event->attributes()->club;
				$t->idid = $event->attributes()->id;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}

				foreach($id as $a=>$b) {
					if(!$id[$a] || !$nm[$a]) {
						continue;
					}
					$t = new NewHockeyPlayer();
					$t->_sport = 'hockey';
					$t->_type = 'player';
					$t->id = $id[$a];
					$t->runame = $nm[$a];
					$p = $t->Publish();
					if($p) {
						$err[]=$p;
					}
				}
			}



			foreach($match->lineup as $lineup) {
				$t = new NewHockeyLineup();
				$t->_sport = 'hockey';
				$t->_type = 'lineup';
				// $t->id = (int) $match->attributes()->id*1000000
				// 	+ (int) $lineup->attributes()->cb * 100
				// 	+ (int) $lineup->attributes()->no;
				$t->match_id = (int) $match->attributes()->id;
				$t->cb = $lineup->attributes()->cb;
				$t->no = $lineup->attributes()->no;
				$t->player_name = $lineup->attributes()->pl;
				$t->player_id = $lineup->attributes()->IDplayer;
				$t->line = $lineup->attributes()->Line;
				$t->pos = $lineup->attributes()->pos;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}

				if($lineup->attributes()->IDplayer && $lineup->attributes()->pl) {
					$t = new NewHockeyPlayer();
					$t->_sport = 'hockey';
					$t->_type = 'player';
					$t->id = $lineup->attributes()->IDplayer;
					$t->runame = $lineup->attributes()->pl;
					$p = $t->Publish();
					if($p) {
						$err[]=$p;
					}
				}
			}
		}
	}

	return $err;
}

?>