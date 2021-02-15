<?php

/**
 * Main checking function, calls children checking functions
 */
function check ($givenExpr, $base, $op) {
	checkBaseOpe($givenExpr, $base, $op);
	checkExpr($givenExpr, $base, $op);
}

/**
 * Check given base and operators
 */
function checkBaseOpe ($givenExpr, $base, $op) {
	$verb = "Checking errors in base and operators...";
	verbose($verb);
	$A = 0;
	//checking length
	if (strlen($op) < 7) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Need 7 defined operators to replace +-*/%(), only ".
			strlen($op)." given", true);
	}
	if (strlen($op) > 7){
		displayWarning("No need to define more than 7 operators: +-*/%()", true, false);
		$A += 2;
	}
	
	//checking similar chars
	//checking base
	$chars = [];
	for ($i = 0; $i < strlen($base); $i++) {
		if (isset($chars[$base[$i]]))
			$similar[] = $base[$i];
		else
			$chars[$base[$i]] = 0;
	}
	if (isset($similar)){
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Cannot duplicate chars \"".implode("\" \"", $similar).
			"\" in base", false);
	}
	//checking operators
	$chars = [];
	for ($i = 0; $i < strlen($op); $i++) {
		if (isset($chars[$op[$i]]))
			$similar[] = $op[$i];
		else
			$chars[$op[$i]] = 0;
	}
	if (isset($similar)){
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Cannot duplicate chars \"".implode("\" \"", $similar).
			"\" in operators", false);
	}
	//checking both
	for ($i = 0; $i < strlen($base); $i++) {
		if (strpos($op, $base[$i]) !== false)
			$similar[] = $base[$i];
	}
	if (isset($similar)){
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Cannot duplicate chars \"".implode("\" \"", $similar).
			"\" in base and operators", false);
	}

	verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
}

/* 
	$verb = "Checking errors in base and operators...";
	verbose($verb);

	if ($A > 0)
		verbose("\033[s\033[".($A+1)."A\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\033[u");
		// verbose("\033[s\033[".($A+1)."A".$verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\033[u");
	else
		verbose("\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n");

	// if ($A > 0)
		// verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
		// verbose("\033[s\033[".($A+1)."A\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\033[u");
		// verbose("\033[s\033[".($A+1)."A".$verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\033[u");
	// else
		// verbose("\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n");
*/

/**
 * Check given expression
 */
function checkExpr ($givenExpr, $base, $op) {
	$verb = "Checking valid expression...";
	verbose($verb);
	$A = 0;

	if (strlen($givenExpr) === 0) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Empty expression", false);
	}

	$rules = getRules($base, $op);
	$autorized = $base.".".$op;
	$parenthesis = 0;
	
	if (strpos($op[0].$op[1].$op[5].$base, $givenExpr[0]) === false) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Invalid first char: ".$givenExpr."\033[m\n".
			str_repeat(" ", 27)."\033[91m^\033[m", false);
	}
	$len = strlen($givenExpr)-1;
	if (strpos($op[6].$base, $givenExpr[$len]) === false) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Invalid last char: ".$givenExpr."\033[m\n".
			str_repeat(" ", 26+$len)."\033[91m^\033[m", false);
	}

	for ($i = 0; $i < strlen($givenExpr); $i++) {
		$prev = $char ?? null;
		$char = $givenExpr[$i];
		$next = $givenExpr[$i+1] ?? null;
		$flag = false;

		if (strpos($autorized, $char) === false)
			$flag = true;
		
		if(isOperator($char, $op) || $char === ".") {
			$check1 = strpos($rules[$char]["F"], $prev);
			$check2 = strpos($rules[$char]["B"], $next);
			if ($check1 !== false || $check2 !== false)
				$flag = true;
		}

		if ($flag) {
			verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
			displayError("near ".$givenExpr."\033[m\n".
				str_repeat(" ", 12+$i)."\033[91m^\033[m", false);
		}

		if($char === $op[5]) {
			$parenthesis++;
			$lastOpen[] = $i;
		}
		elseif($char === $op[6]) {
			$parenthesis--;
			if ($parenthesis < 0) {
				verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
				displayError("Missing ".$op[5]." to open:\033[m\n\033[41m".
					$givenExpr."\033[m\n".
					str_repeat(" ", $i)."\033[91m^\033[m", false);
			}
		}
	}
	if ($parenthesis !== 0) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", ["A" => $A]);
		displayError("Missing ".$op[6]." to close: \033[m\n\033[41m".$givenExpr.
			"\033[m\n".str_repeat(" ", $lastOpen[count($lastOpen)-1-$parenthesis] ?? $lastOpen[0]).
			"\033[91m^\033[m", false);
	}
	verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
	// verbose("\033[3D\033[92m".str_repeat(' ', 43-strlen($verb))."[done]\033[m\n");
}

/**
 * Verify if a number with length > 1 have only one coma
 */
function checkLongInt($nb, $verb, $arr = []) {
	if (substr_count($nb, '.') > 1) {
		verbose($verb."\033[3D\033[91m".str_repeat(' ', 42-strlen($verb))."[fail]\033[m\n", $arr);
		displayError('A number can have only one coma, '.
			substr_count($nb, '.').' given for '.$nb, false);
	}
}

/**
 * @return array All forbiden characters foward and backward each operators
 */
function getRules($base, $operators) {
	return ([
		"." => [
			"F" => generateForbidenStr(".+-*/%()", $operators),
			"B" => generateForbidenStr(".+-*/%()", $operators)
		],
		$operators[0] ?? "+" => [
			"F" => "",
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[1] ?? "-" => [
			"F" => "",
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[2] ?? "*" => [
			"F" => generateForbidenStr("+-*/%(", $operators),
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[3] ?? "/" => [
			"F" => generateForbidenStr("+-*/%(", $operators),
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[4] ?? "%" => [
			"F" => generateForbidenStr("+-*/%(", $operators),
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[5] ?? "(" => [
			"F" => $base,
			"B" => generateForbidenStr("*/%)", $operators)
		],
		$operators[6] ?? ")" => [
			"F" => generateForbidenStr("+-*/%(", $operators),
			"B" => $base
		],
	]);
}

/**
 * Generate a fobiden characters list for each operators according to the
 *  operator's reference
 * 
 * @return string
 */
function generateForbidenStr($forbiden, $operators) {
	$reference = "+-*/%()";
	$toRefuse = "";
	for ($i=0; $i<strlen($forbiden); $i++) {
		if ($forbiden[$i] === ".")
			$toRefuse .= ".";
		else
			$toRefuse .= $operators[strpos($reference, $forbiden[$i])];
			// ".";
	}

	return ($toRefuse);
}

/**
 * Check if verbose is allowed from command line
 */
function checkVerbose($argv) {
	if (in_array("-v", $argv)) {
		// $_ENV["VERBOSE"] = true;
		putenv("VERBOSE=true");
		array_splice($argv, array_search ('-v', $argv), 1);
	}
	// var_dump($_ENV("VERBOSE"));
	// var_dump(getenv("VERBOSE"));
	return ($argv);
}

function checkIsSetBaseOpe($expr, $argv) {
	if ((!isset($argv[2]) || !isset($argv[3])) && 
	preg_match_all("/^[0-9\+\-\*\/\%\(\)]{0,}$/", $expr) === 0)
	{
		displayWarning('For not classic expressions, '.
			'you might need to specify base and'."\n".'operators after the expression', false, false);
	}
}
