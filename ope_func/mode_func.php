<?php

function	slice_mod($result, $operator, $len, $signe)
{
	$c = strlen($result);
	$c2 = 0;
	$tmp = "";
	while (--$c > -1)
	{
		if ($len > 0 && $c2 === $len)
			$tmp .= '.';
		$tmp .= $result[$c];
		$c2++;
	}
	if ($signe === -1)
		return ($operator[1] . strrev($tmp));
	return (strrev($tmp));
}

function	get_decimal($tmp1, $tmp2)
{
	$len = 0;
	if (isset($tmp1[1]))
	{
		if (isset($tmp2[1]))
		{
			if (strlen($tmp1[1]) > strlen($tmp2[1]))
				$len = strlen($tmp1[1]);
			else
				$len = strlen($tmp2[1]);
		}
		else
			$len = strlen($tmp1[1]);
	}
	else if (isset($tmp2[1]))
		$len = strlen($tmp2[1]);
	return ($len);
}

function	quest_value(&$str1, &$str2, $base)
{
	$t1 = explode('.', $str1);
	$t2 = explode('.', $str2);
	if (!isset($t1[1]))
		$t1[1] = "";
	else if (!isset($t2[1]))
		$t2[1] = "";
	if (strlen($t1[1]) > strlen($t2[1]))
	{
		while (strlen($t1[1]) !== strlen($t2[1]))
			$t2[1] .= $base[0];
	}
	else if (strlen($t1[1]) < strlen($t2[1]))
	{
		while (strlen($t1[1]) !== strlen($t2[1]))
			$t1[1] .= $base[0];
	}
	$str1 = implode("", $t1);
	$str2 = implode("", $t2);
}