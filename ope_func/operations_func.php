<?php

/* ************************************************************************** */
/*                                                                            */
/*   Name :operations_func.php                        :::     ::::::::      :::    */
/*                                             :+:      :+:   :+:    :+:      */
/*   By: skarma                              +:+ +:+   +:+   +:+   +:+ +:+    */
/*                                         +#+  +:+   +#+   +#+  +#+  +:+     */
/*   Create:2019/07/26 12:21:21          +#+#+#+#+#+ +#+   +#+ +#+#+#+#+#+    */
/*                                            #+#   #+#   #+#       #+#       */
/*         by skarma                        ###   #########       ###.error   */
/*                                                                            */
/* ************************************************************************** */

include_once("support_func.php");
include_once("add_sub_func.php");
include_once ("mode_func.php");
include_once("div.php");
include_once("supp_mul.php");

function	add(&$str1, &$str2, $base, $operator = "+-")
{
	$signe = get_signe($str1, $str2, $operator);
	$str1 = get_calc_str($str1, $signe[2]);
	$str2 = get_calc_str($str2, $signe[3]);
	equalize($str1, $str2, $base);
	if (compare_expession($str1, $str2, $base) < 0)
	{
		$table = get_table(1)[$signe[0]][$signe[1]][-1];
		return ($table[0]($str2, $str1, $base, $table[1], $operator));
	}
	else
	{
		$table = get_table(1)[$signe[0]][$signe[1]][1];
		return ($table[0]($str1, $str2, $base, $table[1], $operator));
	}
}

function	compare_expession($str1, $str2, $base)
{
	$c = -1;
	$len = strlen($str1);
	while (++$c < $len)
	{
		if (strpos($base, $str1[$c]) !== strpos($base, $str2[$c]))
			return (strpos($base, $str1[$c]) - strpos($base, $str2[$c]));
	}
	return (0);
}
function	sub($str1, $str2, $base, $operator = "+-")
{
	$signe = get_signe($str1, $str2, $operator);
	$str1 = get_calc_str($str1, $signe[2]);
	$str2 = get_calc_str($str2, $signe[3]);
	equalize($str1, $str2, $base);
	if (compare_expession($str1, $str2, $base) < 0)
	{
		$table = get_table(-1)[$signe[0]][$signe[1]][-1];
		return ($table[0]($str2, $str1, $base, $table[1], $operator));
	}
	else
	{
		$table = get_table(-1)[$signe[0]][$signe[1]][1];
		return ($table[0]($str1, $str2, $base, $table[1], $operator));
	}
}

function	mul($str1, $str2, $base, $operator = "+-")
{
	$signe = get_signe($str1, $str2, $operator);
	$str1 = get_calc_str($str1, $signe[2]);
	$str2 = get_calc_str($str2, $signe[3]);
	$nb = get_full_number($str1, $str2);
	equalize($str1, $str2, $base);
	$result = $base[0];
	$tmp = "";
	equalize($tmp, $str2, $base);
	$tmp2 = $base[1];
	equalize($tmp2, $str2, $base);
	while (compare_expession($str2, $tmp, $base) !== 0)
	{
		equalize($str1, $result, $base);
		$result = add_func($result, $str1, $base, 1, $operator);
		$str2 = sub_func($str2, $tmp2, $base, 1, $operator);
	}
	return (slice_mul($result, $base, $signe, $nb, $operator));
}

function	div($str1, $str2, $base, $operator = "+-")
{
	$signe = get_signe($str1, $str2, $operator);
	$str1 = get_calc_str($str1, $signe[2]);
	$str2 = get_calc_str($str2, $signe[3]);
	if (substr_count($str2, $base[0]) === strlen($str2))
	{
		var_dump("bad game");
		return (false);
	}
	else if (substr_count($str1, $base[0]) === strlen($str1))
		return ($base[0]);
	else if (strpos($str2, '.') !== false ||
		strpos($str1, '.') !== false)
			quest_value($str1, $str2, $base);
	$result = div_func($str1, $str2, $base, $operator);
	if ($signe[0] === -1 && $signe[1] === -1)
		return ($result);
	else if ($signe[0] === -1 || $signe[1] === -1)
		return ($operator[1] . $result);
	return ($result);
}

function	mod($str1, $str2, $base, $operator = "+-")
{
	$signe = get_signe($str1, $str2, $operator);
	$str1 = get_calc_str($str1, $signe[2]);
	$str2 = get_calc_str($str2, $signe[3]);
	$tmp1 = explode('.', $str1);
	$tmp2 = explode('.', $str2);
	if (substr_count($str2, $base[0]) === strlen($str2))
	{
		var_dump("bad game");
		return (false);
	}
	else if (strpos($str2, '.') !== false ||
		strpos($str1, '.') !== false)
		quest_value($str1, $str2, $base);
	$dev = div_func($str1, $str2, $base, $operator, true);
	$len = get_decimal($tmp1, $tmp2);
	return (slice_mod($dev, $operator, $len, $signe[1]));
}
