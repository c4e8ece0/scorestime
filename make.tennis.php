<?php

class NewTennisTournament extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $enname;
	public $runame;
	public $date;
	public $prize_money;
	public $surface;
	public $country_id;
	public $country;
}

class NewTennisMatch extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $comp_id;
	public $round;
	public $date;
	public $time;
	public $player1;
	public $player2;
	public $player_id1;
	public $player_id2;
	public $playerb_id1;
	public $playerb_id2;
}

class NewTennisPlayer extends NewData
{
	public $_sport;
	public $_type;
	public $id;
	public $prefix;
	public $enname;
	public $runame;
}

class NewTennisStatus extends NewData
{
	public $_sport;
	public $_type;
	public $match_id;
	public $status;
	public $set1_1;
	public $set1_2;
	public $set2_1;
	public $set2_2;
	public $set3_1;
	public $set3_2;
	public $set4_1;
	public $set4_2;
	public $set5_1;
	public $set5_2;
	public $gres1;
	public $gres2;
	public $winner;
	public $serving;
	public $r1;
	public $r2;
}


//
function tennis_fixtures($xml) {
	// ob_start();
	// print_r($xml);
	// file_put_contents('buf', ob_get_clean());
	// exit();
	// return array();
	$err = array();
	foreach($xml->tournament as $tournament) {
		$t = new NewTennisTournament();
		$t->_sport = 'tennis';
		$t->_type = 'competition';
		$t->id = $tournament->attributes()->id;
		$t->runame = $tournament->attributes()->name;
		$t->date = $tournament->attributes()->date;
		$t->prize_money = $tournament->attributes()->prize_money;
		$t->surface = $tournament->attributes()->surface;
		$t->country_id = $tournament->attributes()->countryID;
		$t->country = $tournament->attributes()->country;
		$p = $t->Publish();
		if($p) {
			$err[]=$p;
		}

		foreach($tournament->match as $match) {
			$t = new NewTennisMatch();
			$t->_sport = 'tennis';
			$t->_type = 'match';
			$t->id = $match->attributes()->id;
			$t->comp_id = $tournament->attributes()->id;
			$t->round = $match->attributes()->round;
			$t->date = $tournament->attributes()->date;
			$t->time = $match->attributes()->time;
			$t->player1 = $match->attributes()->player1;
			$t->player2 = $match->attributes()->player2;
			$t->player_id1 = $match->attributes()->player1ID;
			$t->player_id2 = $match->attributes()->player2ID;
			$t->playerb_id1 = $match->attributes()->playerB1ID;
			$t->playerb_id2 = $match->attributes()->playerB2ID;
			$p = $t->Publish();
			if($p) {
				$err[]=$p;
			}

			if($match->player1) {
				$t = new NewTennisPlayer();
				$t->_sport = 'tennis';
				$t->_type = 'player';
				$t->prefix = $match->player1->attributes()->prefix;
				$t->id = $match->player1->attributes()->playerid;
				$t->runame = $match->player1->attributes()->playername;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if($match->player1 && ($match->player1->attributes()->playerbid || $match->player1->attributes()->playerbname)) {
				$t = new NewTennisPlayer();
				$t->_sport = 'tennis';
				$t->_type = 'player';
				$t->prefix = $match->player1->attributes()->prefix;
				$t->id = $match->player1->attributes()->playerbid;
				$t->runame = $match->player1->attributes()->playerbname;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if(@$match->player2) {
				$t = new NewTennisPlayer();
				$t->_sport = 'tennis';
				$t->_type = 'player';
				$t->prefix = @$match->player2->attributes()->prefix;
				$t->id = @$match->player2->attributes()->playerid;
				$t->runame = @$match->player2->attributes()->playername;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if($match->player2 && ($match->player2->attributes()->playerbid || $match->player2->attributes()->playerbname)) {
				$t = new NewTennisPlayer();
				$t->_sport = 'tennis';
				$t->_type = 'player';
				$t->prefix = $match->player2->attributes()->prefix;
				$t->id = $match->player2->attributes()->playerbid;
				$t->runame = $match->player2->attributes()->playerbname;
				$p = $t->Publish();
				if($p) {
					$err[]=$p;
				}
			}

			if($match->status_result) {
				$t = new NewTennisStatus();
				$t->_sport = 'tennis';
				$t->_type = 'match_status';
				$t->match_id = $match->attributes()->id;
				$t->status = $match->status_result->attributes()->status;
				$t->set1_1 = (int) $match->status_result->attributes()->set1_1;
				$t->set1_2 = (int) $match->status_result->attributes()->set1_2;
				$t->set2_1 = (int) $match->status_result->attributes()->set2_1;
				$t->set2_2 = (int) $match->status_result->attributes()->set2_2;
				$t->set3_1 = (int) $match->status_result->attributes()->set3_1;
				$t->set3_2 = (int) $match->status_result->attributes()->set3_2;
				$t->set4_1 = (int) $match->status_result->attributes()->set4_1;
				$t->set4_2 = (int) $match->status_result->attributes()->set4_2;
				$t->set5_1 = (int) $match->status_result->attributes()->set5_1;
				$t->set5_2 = (int) $match->status_result->attributes()->set5_2;
				$t->gres1 = (int) $match->status_result->attributes()->gRes1;
				$t->gres2 = (int) $match->status_result->attributes()->gRes2;
				$t->winner = (int) $match->status_result->attributes()->winner;
				$t->serving = (int) $match->status_result->attributes()->serving;
				$t->r1 = (int) $match->status_result->attributes()->res1;
				$t->r2 = (int) $match->status_result->attributes()->res2;
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