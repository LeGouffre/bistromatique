<?php

include("operations_func.php");

//1038173

function main()
{
//	$str = "11.6";
//	$str2 = "2.6";
//	=>1.2

//	$str = "2.1";
//	$str2 = "16";
//		$str = "11.6";
//	$str2 = "2.44";
//	=>1.84

//	$str = "0.5";
//	$str2 = "0.4";
//	=>0.1

//	$str = "-11.6";
//	$str2 = "-2.4";
//	=>-2.0

	$str = "-6";
	$str2 = "2";
//	=>0.316
//	$str = "9.x";
//	$str2 = "#";
//	$base = "&{xt4#6+@9";
//	echo (mul($str, $str2, $base));
//	echo ("\n");
	$base = "0123456789";
//	echo (div($str, $str2, $base, "abcdefg"));
	echo (mod($str, $str2, $base));
//	echo ("\n");
//	echo (div($str, $str2, $base));
	echo ("\n");
}

main();
