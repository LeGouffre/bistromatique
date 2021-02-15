<?php

function dd ($var) {
	var_dump($var);
	die();
}

function _light_arr_dump($arr, $indent = 1) {
	foreach ($arr as $key => $value) { 
		if (!is_array($value)) {
			echo str_repeat(" ", $indent)."[$key] => $value\n";
			// echo str_repeat(" ", $indent)."[$key] => $value; ";
		}
		else {
			echo str_repeat(" ", $indent)."[$key]↴ \n";
			_light_arr_dump($value, $indent+3);
		}
	}
	// echo " [$key](".gettype($value)."): $value\n";
}

function displayError($msg, $inverbose = false) {
	if ($inverbose){
		verbose("\n");
		// 	verbose("\033[s\033[".($goUp === null ? 0 :$goUp+1)."A\033[3D\033[1;91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\033[u\r");
	}

	echo "\033[41mError: ".$msg."\033[m\n";
	// sleep(1);
	die(1);
}

function displayWarning($msg, $inverbose = false, $fatal = true) {
	if ($inverbose)
		verbose("\n");
	$msg = str_replace("\n", "\033[m\n\033[48;2;255;165;0m\033[30m", $msg);
	echo "\033[48;2;255;165;0m\033[30mWarning: ".$msg."\033[m\n";
	if ($fatal)
		die(0);

	// if ($inverbose)
	// 	verbose("\033[u");
	// sleep(1);
}

function verbose($verb, $movement = []) {
	$reverseMovement = ["A" => "B", "B" => "A", "C" => "D", "D" => "C"];
	// var_dump($movement);
	// var_dump($verb);
	if (getenv("VERBOSE") === "true") {
		// echo "\033[s";
		foreach ($movement as $key => $value) {
			if ($value !== 0){
				// echo "$value $key\n";
				echo "\033[".$value.$key;
			}
		}
		echo "\r".$verb;
		foreach ($movement as $key => $value) {
			if ($value !== 0)
				echo "\033[".$value.$reverseMovement[$key];
		}
		// sleep(1);
		// echo "\033[u";
	}
}
