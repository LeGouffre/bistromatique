<?php

function my_split ($givenExpr, $base, $operators)
{
	check($givenExpr, $base, $operators);
	$verb = "Spliting the expression...";
	verbose($verb);
	$A = 0;

	$longint = "";
	$parenthesis = 0;
	$expr = [];

	for ($i = 0; $i < strlen($givenExpr); $i++) {
		$lastChar = $char ?? null;
		$char = $givenExpr[$i];

		if (( /* If is actual and next are base */
				((strpos($base, $char) !== false) || $char === ".") /* if char in base */
				&& 
				( /* if next char in base */
					$i+1 < strlen($givenExpr) && 
					(
						(strpos($base, $givenExpr[$i+1]) !== false && strpos($base, $givenExpr[$i+1]) !== false)
						|| $givenExpr[$i+1] === "."
					)
				)
			) || 
			( /* Is char a sign associated to base */
				( /* If prev char was operator and char is sign */
					(isOperator($lastChar, $operators) || $lastChar === null)
					&&
					(
						$char === $operators[0] /* + */
						||
						$char === $operators[1] /* - */
					)
				)
				&& 
				( /* If next char is not ( or ) */
					$i+1 < strlen($givenExpr) && 
					(
						$givenExpr[$i+1] !== $operators[5] /* ( */
						&&
						$givenExpr[$i+1] !== $operators[6] /* ) */
					)
				)
			)
		) {
			$longint .= $char;
		}
		else
		{
			if($longint !== "") //If it was a long number composed of many chars
			{
				$longint .= $char;
				checkLongInt($longint, $verb);
				array_push($expr, $longint);
				$longint = "";
			}
			else //if it's a single int or an operator
				array_push($expr, $char);
		}
	}

	verbose($verb."\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n", ["A" => $A]);
	// verbose("\033[3D\033[92m".str_repeat(' ', 42-strlen($verb))."[done]\033[m\n");
	return ($expr);
}

// my_split($argv[1], $argv[2], $argv[3]);

/* function checkTrickyExpr ($expr) {
	if (count($expr) === 0)
		displayError("Merci de renseigner une expression");
		// return 1;
	elseif (count($expr) === 1)
		return 1;
	else
		return 0;
} */
