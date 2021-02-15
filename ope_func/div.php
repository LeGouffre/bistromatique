<?php

function	how_many($str1, $str2, &$res, $base, $operator)
{
	$count = $base[0];
	$tmp = $base[1];
	$result = $base[0];
	equalize($result, $str1, $base);
	while (sub($str1, $result, $base, $operator)[0] !== $operator[1])
	{
		$count = add($tmp, $count, $base, $operator);
		$result = mul($str2, $count, $base, $operator);
	}
	$count = sub($count, $tmp, $base, $operator);
	$res =  mul($str2, $count, $base, $operator);
	return ($count);
}

function	sub_zero($str, &$result,$base, $operator, $flag = false)
{
	if (sub($str[0], $str[1], $base, $operator)[0] === $operator[1])
	{
		if ($flag === true)
			$result .= $base[0];
		if (strpos($result, '.') === false)
			$result .= '.';
		$nb = $str[0] . $base[0];
		$str[0] .= $base[0];
		while (sub($nb, $str[1], $base, $operator)[0] === $operator[1])
		{
			$nb .= $base[0];
			$result .= $base[0];
			$str[0] .= $base[0];
		}
	}
	return ($str[0]);
}

function	remove_useless_zero($str, $base)
{
	$c = -1;
	$len = strlen($str);
	$flag = false;
	$tmp = "";
	while (++$c < $len)
	{
		if ($flag === false && ($str[$c] !== $base[0]))
			$flag = true;
		if ($flag === true)
			$tmp .= $str[$c];
	}
	return ($tmp);
}

function	get_div($str1,$str2, &$nb, $operator, $base, &$count)
{
	$nbTmp = $nb;
	while (sub($nbTmp, $str2, $base, $operator)[0] === $operator[1])
	{
		$count++;
		if (!isset($str1[$count]))
			return (false);
		$nb.= $str1[$count];
		$nbTmp = $nb;
	}
	return (true);
}

function	div_func($str1, $str2, $base, $operator, $flag_mod = false)
{
	$nb = $str1[0];
	$n = 0;
	$c = 0;
	$result = "";
	$rest = "";
	$flag = false;
	$save = $str1;
	$str1 = sub_zero([$str1, $str2], $result, $base, $operator, true);
	get_div($str1, $str2, $nb,$operator, $base, $n);
	if ($flag_mod === true && isset($result[0]) && $result[0] === $base[0] &&
			isset($result[1]) && $result[1] === '.')
		return ($save);
	while ($nb !== "" && ++$c < 8)
	{
		$result .= how_many($nb, $str2,$rest, $base, $operator);
		$nb = sub($nb, $rest, $base, $operator);
		if (isset($str1[$n + 1]))
			$nb .= $str1[++$n];
		else if (remove_useless_zero($nb, $base) !== "" &&
			sub($nb, $str2, $base, $operator)[0] === $operator[1])
		{
			if ($flag_mod === true)
				return ($nb);
			$nb = sub_zero([$nb, $str2], $result, $base, $operator);
			$flag = true;
		}
		if ($flag === true)
			$c++;
		$nb = remove_useless_zero($nb, $base);
	}
	if ($flag_mod === true)
		return ("0");
	return ($result);
}