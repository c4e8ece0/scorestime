<?php

class NewSoccerGame extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $comp_id;
	public $date;
	public $time;
	public $club1;
	public $club2;
	public $club_id1;
	public $club_id2;
	public $day;
}

class NewSoccerResult extends NewData
{
	public $_sport;
	public $_type;
	public $ss;
	public $r1;
	public $r2;
	public $rh1;
	public $rh2;
	public $rf1;
	public $rf2;
	public $re1;
	public $re2;
	public $rp1; // penalties ??
	public $rp2; // penalties ??
	public $minute;
}

class NewSoccerEvent extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $match_id;
	public $evid;
	public $player_id;
	public $player_name;
	public $type;
	public $minute;
	public $cb;
}

class NewSoccerPenalties extends NewData
{
	public $_sport;
	public $_type;
	public $pen_r1;
	public $pen_r2;
}

class NewSoccerStadium extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $runame;
}

class NewSoccerMatchStadium extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $stadium_id;
}



class NewSoccerLineup extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $match_id; // ++ clubid
	public $cb;
	public $no;
	public $player_id;
	public $player_name;
}

class NewSoccerSubstitution extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $idpl_out;
	public $idpl_in;
	public $cb;
	public $minute;
	public $pl_out;
	public $pl_in;
}

class NewSoccerBench extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $idpl;
	public $cb;
	public $no;
	public $pl;
}

class NewSoccerCoach extends NewData
{
	public $_sport;
	public $_type;
	public $id; // match_id+e1+cb
	public $match_id;
	public $cb;
	public $runame;
}

class NewSoccerReferee extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $match_id;
	public $runame;
}

class NewSoccerPlayer extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $runame;
	public $enname;
}

class NewSoccerClub extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $runame;
	public $enname;
}

class NewSoccerStats extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $descr;
	public $val1;
	public $val2;
}



function soccer_fixtures($xml)
{
	// ob_start();
	// print_r($xml);
	// file_put_contents('buf', ob_get_clean());
	// exit;

	$err = array();
	foreach($xml->cp as $competition) {
		$t = new NewCompetition();
		$t->_sport = 'soccer';
		$t->_type = 'competition';
		$t->id = $competition->attributes()->id;
		$t->runame = $competition->attributes()->nm;
		$t->date = $competition->attributes()->dt;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}

		foreach($competition->gm as $game) {
			$t = new NewSoccerGame();
			$t->_sport = 'soccer';
			$t->_type = 'match';
			$t->id = $game->attributes()->id;
			$t->comp_id = $competition->attributes()->id;
			$t->date = $competition->attributes()->dt;
			$t->time = $game->attributes()->tm;
			$t->club_id1 = $game->attributes()->cid1;
			$t->club_id2 = $game->attributes()->cid2;
			$t->club1 = $game->attributes()->c1;
			$t->club2 = $game->attributes()->c2;
			$t->day = (int)$game->attributes()->day;
				// var_dump($t);
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			$t = new NewSoccerClub();
			$t->_sport = 'soccer';
			$t->_type = 'club';
			$t->id = $game->attributes()->cid1;
			$t->runame = $game->attributes()->c1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}			

			$t = new NewSoccerClub();
			$t->_sport = 'soccer';
			$t->_type = 'club';
			$t->id = $game->attributes()->cid1;
			$t->runame = $game->attributes()->c1;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}			


			if($game->sr) {
				$t = new NewSoccerResult();
				$t->_sport = 'soccer';
				$t->_type = 'match_status';
				$t->match_id = $game->attributes()->id;
				$t->ss = $game->sr->attributes()->ss;
				$t->r1 = (int) $game->sr->attributes()->r1;
				$t->r2 = (int) $game->sr->attributes()->r2;
				$t->rh1 = (int) $game->sr->attributes()->rH1;
				$t->rh2 = (int) $game->sr->attributes()->rH2;
				$t->rf1 = (int) $game->sr->attributes()->rF1;
				$t->rf2 = (int) $game->sr->attributes()->rF2;
				$t->re1 = (int) $game->sr->attributes()->rE1;
				$t->re2 = (int) $game->sr->attributes()->rE2;
				$t->rp1 = (int) $game->sr->attributes()->rP1;
				$t->rp2 = (int) $game->sr->attributes()->rP2;
				$t->minute = $game->sr->attributes()->minute;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($game->ev as $event) {
				$t = new NewSoccerEvent();
				$t->_sport = 'soccer';
				$t->_type = 'event';
				$t->id = (int) $game->attributes()->id *1000 + (int)$event->attributes()->id;
				
				$t->match_id = $game->attributes()->id;
				$t->evid = $event->attributes()->id;
				$t->player_id = (int) $event->attributes()->IDpl;
				$t->player_name = $event->attributes()->pl;
				$t->type = $event->attributes()->tp;
				$t->minute = $event->attributes()->mt;
				$t->cb = $event->attributes()->cb;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if($game->stadium) {
				$t = new NewSoccerStadium();
				$t->_sport = 'soccer';
				$t->_type = 'stadium';
				$t->id = $game->stadium->attributes()->id;
				$t->runame = $game->stadium->attributes()->nm;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}

				$t = new NewSoccerMatchStadium();
				$t->_sport = 'soccer';
				$t->_type = 'match_stadium';
				$t->stadium_id = $game->stadium->attributes()->id;
				$t->match_id = $game->attributes()->id;
				$t->runame = $game->stadium->attributes()->nm;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}

			}

			foreach($game->coach as $coach) {
				$t = new NewSoccerCoach();
				$t->_sport = 'soccer';
				$t->_type = 'coach';
				$t->id = (int)$game->attributes()->id*10 + (int)$coach->attributes()->cb;
				$t->match_id = (int)$game->attributes()->id;
				$t->runame = $coach->attributes()->nm;
				$t->cb = $coach->attributes()->cb;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if($game->referee) {
				$t = new NewSoccerReferee();
				$t->_sport = 'soccer';
				$t->_type = 'referee';
				$t->id = $game->referee->attributes()->id;
				$t->match_id = $game->attributes()->id;
				$t->runame = $game->referee->attributes()->nm;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($game->lineup as $lineup) {
				$t = new NewSoccerLineup();
				$t->_sport = 'soccer';
				$t->_type = 'lineup';
				$t->id = (int) $game->attributes()->id*1000
					+ (int) $lineup->attributes()->cb * 100
					+ (int) $lineup->attributes()->no;
				$t->match_id = (int) $game->attributes()->id;
				$t->cb = $lineup->attributes()->cb;
				$t->no = $lineup->attributes()->no;
				$t->player_name = $lineup->attributes()->pl;
				$t->player_id = $lineup->attributes()->IDpl;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}

				$t = new NewSoccerPlayer();
				$t->_sport = 'soccer';
				$t->_type = 'player';
				$t->id = $lineup->attributes()->IDpl;
				$t->runame = $lineup->attributes()->pl;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($game->substitution as $substitution) {
				$t = new NewSoccerSubstitution();
				$t->_sport = 'soccer';
				$t->_type = 'substitution';
				$t->match_id = $game->attributes()->id;
				$t->idpl_out = (int) $substitution->attributes()->IDpl_out;
				$t->idpl_in = (int) $substitution->attributes()->IDpl_in;
				$t->cb = (int) $substitution->attributes()->cb;
				$t->minute = (int) $substitution->attributes()->mt;
				$t->pl_out = $substitution->attributes()->pl_out;
				$t->pl_in = $substitution->attributes()->pl_in;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($game->bench as $bench) {
				$t = new NewSoccerBench();
				$t->_sport = 'soccer';
				$t->_type = 'bench';
				$t->match_id = $game->attributes()->id;
				$t->idpl = (int) $bench->attributes()->IDpl;
				$t->cb = (int) $bench->attributes()->cb;
				$t->no = (int) $bench->attributes()->no;
				$t->pl = $bench->attributes()->pl;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			foreach($game->stats as $stats) {
				$t = new NewSoccerStats();
				$t->_sport = 'soccer';
				$t->_type = 'stats';
				$t->match_id = $game->attributes()->id;
				$t->descr = $stats->attributes()->descr;
				$t->val1 = $stats->attributes()->val1;
				$t->val2 = $stats->attributes()->val2;
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