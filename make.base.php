<?php

// Обработка xml-элемента
function onejob($name, $data) {
	if(!$data) {
		return array();
	}
	file_put_contents('last_xml', $data);
	// $xml = new SimpleXMLElement($data);

	libxml_use_internal_errors(true);
	$xml = simplexml_load_string($data);
	if ($xml == false) {
		printf("BAD_XML_FILE(%s)\n", $name);
		return array();
		// details, if needed
		$lns = explode("\n", $data);
	    $errors = libxml_get_errors();
	    foreach ($errors as $error) {
	        print_r($error);//display_xml_error($error, $lns);
	    }
	    libxml_clear_errors();
	    return array('xml_errors');
	}

	if(!count($xml)) {
		return array();
	}
	list($act) = explode('.', $name);
	$act = preg_replace('#[0-9]*#', '', $act);

	$tr = array(
		'type=competitions' => 'all_competitions',

		'sport=baseball__type=fixtures' => 'baseball_fixtures',
		'sport=baseball' => 'baseball_fixtures',

		'sport=basketball__type=fixtures' => 'basketball_fixtures',
		'sport=basketball' => 'basketball_fixtures',

		'sport=tennis__type=fixtures' => 'tennis_fixtures',
		'sport=tennis' => 'tennis_fixtures',

		'sport=soccer__type=fixtures' => 'soccer_fixtures',
		'sport=soccer' => 'soccer_fixtures',
		'sport=soccer__type=table__id=' => 'soccer_table',

		'sport=handball__type=fixtures' => 'handball_fixtures',
		'sport=handball' => 'handball_fixtures',

		'sport=hockey__type=fixtures' => 'hockey_fixtures',
		'sport=hockey' => 'hockey_fixtures',

		'sport=volleyball__type=fixtures' => 'volleyball_fixtures',
		'sport=volleyball' => 'volleyball_fixtures',

		'sport=rugby__type=fixtures' => 'rugby_fixtures',
		'sport=rugby' => 'rugby_fixtures',

		'sport=football__type=fixtures' => 'football_fixtures',
		'sport=football' => 'football_fixtures',


		'sport=cricket__type=fixtures' => 'cricket_fixtures',
		'sport=futsal__type=fixtures' => 'futsal_fixtures',
		'sport=rugbyl__type=fixtures' => 'rugbyl_fixtures',
		'sport=waterpolo__type=fixtures' => 'waterpolo_fixtures',
		'sport=beachfootball__type=fixtures' => 'beachfootball_fixtures',
	);
	if(isset($tr[$act])) {
		return call_user_func_array($tr[$act], array(new SimpleXMLElement($data)));
	} else {
		unknown_func($act, $name, $data);
	}
}

// Если обработчик неизвестен
function unknown_func($act, $name, $data) {
	printf("UNKNOWN TEMPLATE (%s)\n", $act);
	ob_start();
	print $name."\n";
	print "\n\n";
	print_r(new SimpleXMLElement($data));
	print "\n\n";
	print_r($data);
	file_put_contents('last_error_data', ob_get_clean());
	printf("DIED!!!");
	exit(1);
}

?>