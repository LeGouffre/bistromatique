<?php

/* ************************************************************************** */
/*                                                                            */
/*   Name :support_func.php                      :::     ::::::::      :::    */
/*                                             :+:      :+:   :+:    :+:      */
/*   By: skarma                              +:+ +:+   +:+   +:+   +:+ +:+    */
/*                                         +#+  +:+   +#+   +#+  +#+  +:+     */
/*   Create:2019/07/26 12:18:05          +#+#+#+#+#+ +#+   +#+ +#+#+#+#+#+    */
/*                                            #+#   #+#   #+#       #+#       */
/*         by skarma                        ###   #########       ###.error   */
/*                                                                            */
/* ************************************************************************** */

function	get_table($n)
{
	$sub_table =
		[1 =>
		[
			1 => [ 1 => [ "sub_func", 1], -1 => [ "sub_func", -1]],
			-1 => [ 1 => [ "add_func", 1], -1 => [ "add_func", 1]],
		],
		-1 =>
			[
			1 => [ 1 => [ "add_func", -1], -1 => ["add_func", -1]],
			-1 =>[ 1 => ["sub_func", -1], -1 => ["sub_func", 1]],
			]
		];
	$add_table =
		[1 =>
		[
			1 => [1 => ["add_func", 1], -1 => ["add_func", 1]],
			-1 => [1 => ["sub_func", 1], -1 => ["sub_func", -1]]
		],
		-1 =>
			[
			1 =>[1 => ["sub_func", -1], -1 => ["sub_func", 1]],
			-1 =>[1 => ["add_func", -1], -1 => ["add_func", -1]]
			],
		];
	if ($n > 0)
		return ($add_table);
	else
		return ($sub_table);
}


function	get_signe($str1, $str2, $opeT)
{
	$signe = [1, 1];
	$c1 = 0;
	while ($str1[$c1] ===  $opeT[0] || $str1[$c1] ===  $opeT[1])
	{
		if ($str1[$c1] === $opeT[1])
			$signe[0] = $signe[0] * -1;
		$c1++;
	}
	$signe[2] = $c1;
	$c1 = 0;
	while ($str2[$c1] === $opeT[0] || $str2[$c1] === $opeT[1])
	{
		if ($str2[$c1] === $opeT[1])
			$signe[1] = $signe[1] * -1;
		$c1++;
	}
	$signe[3] = $c1;
	return ($signe);
}

function	get_calc_str($str, $count)
{
	$c = -1;
	$len = strlen($str);
	$str_rep = "";
	while (++$c < $len)
	{
		if ($c >= $count)
			$str_rep .= $str[$c];
	}
	return ($str_rep);
}

function	push_zero(&$str1, &$str2, $base, $flag)
{
	if ($flag === true)
	{
		if (strlen($str1) > strlen($str2))
		{
			while (strlen($str1) != strlen($str2))
				$str2 = $base[0] . $str2;
		}
		else
		{
			while (strlen($str1) != strlen($str2))
				$str1 = $base[0] . $str1;
		}
	}
	else
	{
		if (strlen($str1) > strlen($str2))
		{
			while (strlen($str1) != strlen($str2))
				$str2 .= $base[0];
		}
		else
		{
			while (strlen($str1) != strlen($str2))
				$str1 .= $base[0];
		}
	}
}

function	equalize(&$str1, &$str2, $base)
{
	$banana1 = explode('.',$str1);
	$banana2 = explode('.',$str2);
	push_zero($banana1[0], $banana2[0], $base, true);
	push_zero($banana1[1], $banana2[1], $base, false);
	if (isset($banana1[1]) || isset($banana2[1]))
	{
		$str1 = implode('.', $banana1);
		$str2 = implode('.', $banana2);
	}
	else
	{
		$str1 = $banana1[0];
		$str2 = $banana2[0];
	}
}

