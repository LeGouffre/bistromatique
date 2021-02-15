<?php

require_once(__DIR__ . '/eval_require.php');

function eval_expr ($expression, $base, $op) {
	$verb = "Converting expression to RPN...";
	verbose($verb);
	$A = 0;

	$operators = getOperators($op);
	// dd($operators);
	$output = [];
	$operations = [];

	while (count($expression) > 0)
	{
		$char = array_shift($expression);

		if (!isOperator($char, $op))
			array_unshift($output, $char);
		else
		{
			if (isset($operations[0]))
			{
				if ($operators[$char]['p'] < $operations[0]["p"] && $operations[0]['c'] !== '(')
				{
					while (count($operations) > 0 && (isset($operations[0]) && $operations[0]['c'] !== '('))
						array_unshift($output, array_shift($operations)['c']);
				}
				elseif ($operators[$char]['p'] === $operations[0]["p"] && $operations[0]['c'] !== '(')
					array_unshift($output, array_shift($operations)['c']);

				if ($char === ')' && $operations[0]['c'] === '(')
					$del = array_shift($operations);
			}

			if($char !== ")")
				array_unshift($operations, ['c' => $char, 'p' => $operators[$char]['p']]);
		}
	}

	while (count($operations) > 0)
		array_unshift($output, array_shift($operations)['c']);

	verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
	// verbose("\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n");
	return (array_reverse($output));
}

function npi ($npi, $base, $op, $pile = [], $verboseFlag = true) {
	$verb = "Calculating...";
	if ($verboseFlag)
		verbose($verb);
	$A = 0;
	$operators = getOperators($op);

	//loop on npi array until [0] is an operator
	while(count($npi) > 0 && !isOperator($npi[0], $op))
		array_unshift($pile, array_shift($npi));

	//get necessary values
	$operator = array_shift($npi);
	$depile1 = array_shift($pile);
	$depile2 = array_shift($pile);

	if (isset($operator))
		$result = $operators[$operator]['f']($depile2, $depile1, $base, $op);
	else
		$result = $depile1;
	array_unshift($pile, $result);

	if (count($npi) > 0)
		$result = npi($npi, $base, $op, $pile, false);
	else
		$result = $pile[0];

	if ($verboseFlag)
		verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
		// verbose("\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n");
	return ($result);
}

function zeroTrim ($result) {
	while ($result[0] === "0" && $result[1] !== ".")
		$result = substr($result, 1);
	if (strpos($result, ".") === false)
		return ($result);
	$result = strrev($result);
	while ($result[0] === "0")
		$result = substr($result, 1);
	if ($result[0] === ".")
		$result = substr($result, 1);
	$result = strrev($result);
	return ($result);
}

/**
 * @return array All operators by default or according to the given ones,
 *  their priority, and now many depiled they need
 */
function getOperators ($op = null) {
	return ([
		$op[0] ?? '+' => [
			'p' => 1,
			'o' => 2,
			'f' => 'add'
		],
		$op[1] ?? '-' => [
			'p' => 1,
			'o' => 2,
			'f' => 'sub'
		],
		$op[2] ?? '*' => [
			'p' => 2,
			'o' => 2,
			'f' => 'mul'
		],
		$op[3] ?? '/' => [
			'p' => 2,
			'o' => 2,
			'f' => 'div'
		],
		$op[4] ?? '%' => [
			'p' => 2,
			'o' => 2,
			'f' => 'mod'
		],
		$op[5] ?? "(" => [
			'p' => 3,
			'o' => 2
		],
		$op[6] ?? ")" => null
	]);
}

/**
 * Check if the given char is an operator
 * 
 * @return bool
 */
function isOperator ($char, $operators = null) {
	return (array_key_exists($char, getOperators($operators)));
}

function fillEnv () {
	$tmpenv = file(".env");
	foreach ($tmpenv as $key => $line) {
		$line = str_replace(' ', '', trim(stripcslashes($line)));
		if ($line[0] !== "#")
			putenv($line);
	}
}

function main ($argv) {
	verbose("\033[92mVerbose: enabled\033[m\n");
	verbose("\033[1;92mStarting\033[m\n");

	if (!isset($argv[1]))
		displayError("Please enter an expression");
	if (count($argv) > 4)
		displayWarning("This script only need 3 arguments", false, false);

	$givenExpr = str_replace(' ', '', trim(stripcslashes($argv[1])));
	checkIsSetBaseOpe($givenExpr, $argv);
	$base = $argv[2] ?? "0123456789";
	$operators = $argv[3] ?? "+-*/%()";
	
	$expr = my_split($givenExpr, $base, $operators);
	$npi = eval_expr($expr, $base, $operators);
	$result = npi($npi, $base, $operators);
	$result = zeroTrim($result);

	if (getenv("VERBOSE") === "true")
		echo "\033[1;92m".$givenExpr." = ".($result ?? $expr[0])."\033[m\n";
	else
		echo ($result ?? $expr[0]);
		// echo ($result ?? $expr[0])."\n";

	// exit($result ?? $expr[0]);
	// echo $result ?? $expr[0];
}

//fillEnv();
main(checkVerbose($argv));
