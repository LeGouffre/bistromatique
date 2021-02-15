<?php

/* ************************************************************************** */
/*                                                                            */
/*   Name :add_sub_func.php                      :::     ::::::::      :::    */
/*                                             :+:      :+:   :+:    :+:      */
/*   By: skarma                              +:+ +:+   +:+   +:+   +:+ +:+    */
/*                                         +#+  +:+   +#+   +#+  +#+  +:+     */
/*   Create:2019/07/26 12:20:28          +#+#+#+#+#+ +#+   +#+ +#+#+#+#+#+    */
/*                                            #+#   #+#   #+#       #+#       */
/*         by skarma                        ###   #########       ###.error   */
/*                                                                            */
/* ************************************************************************** */

function	get_result($n, $base, &$result, &$retnue)
{
	$len = strlen($base);
	if ($n < $len && $n > -1)
	{
		$retnue = 0;
		$result .= $base[$n];
	}
	else if ($n >= $len)
	{
		$retnue = intval($n / $len);
		$result .= $base[$n % $len];
	}
	else if ($n <= $len * -1)
	{
		$retnue = intval($n / $len);
		$result .= ($n  * -1) % $len;
	}
	else if ($n < 0 && $n > $len * -1)
	{
		$result .= $base[$n + $len];
		$retnue = -1;
	}
}

function	convert_to_base($res, $base)
{
	$result = "";
	$tmp = $res . "";
	$tmp = base_convert($tmp, 10, strlen($base));
	$c = -1;
	$len = strlen($tmp);
	while (++$c < $len)
		$result .= $base[intval($tmp[$c])];
	return $result;
}

function	add_func($str1, $str2, $base, $mult, $operator)
{
	$retnue = 0;
	$len = strlen($str1);
	$result = "";
	while (--$len > -1)
	{
		if ($str1[$len] === '.')
			$result .= '.';
		else
		{
			$nb1 = strpos($base, $str1[$len]);
			$nb2 = strpos($base, $str2[$len]);
			$n = ($nb1 + $retnue) + $nb2;
			get_result($n, $base, $result, $retnue);
		}
	}
	if ($retnue !== 0)
	{
		if ($retnue < 0)
			$retnue = $retnue * -1;
		$result .=  convert_to_base($retnue, $base);
	}
	if ($mult === -1)
		return ($operator[1] . strrev($result));
	else
		return (strrev($result));
}

function	sub_func($str1, $str2, $base, $mult, $operator)
{
	$retnue = 0;
	$len = strlen($str1);
	$result = "";
	while (--$len > -1)
	{
		if ($str1[$len] === '.')
			$result .= '.';
		else
		{
			$nb1 = strpos($base, $str1[$len]);
			$nb2 = strpos($base, $str2[$len]);
			$n = ($nb1 + $retnue) - $nb2;
			get_result($n, $base, $result, $retnue);
		}
	}
	if ($retnue !== 0)
	{
		if ($retnue < 0)
			$retnue = $retnue * -1;
		$result .=  convert_to_base($retnue, $base);
	}
	if ($mult === -1)
		return ($operator[1] . strrev($result));
	else
		return (strrev($result));
}
