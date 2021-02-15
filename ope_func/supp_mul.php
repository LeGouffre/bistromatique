<?php

/* ************************************************************************** */
/*                                                                            */
/*   Name :supp_mul.php                          :::     ::::::::      :::    */
/*                                             :+:      :+:   :+:    :+:      */
/*   By: skarma                              +:+ +:+   +:+   +:+   +:+ +:+    */
/*                                         +#+  +:+   +#+   +#+  +#+  +:+     */
/*   Create:2019/07/28 16:39:22          +#+#+#+#+#+ +#+   +#+ +#+#+#+#+#+    */
/*                                            #+#   #+#   #+#       #+#       */
/*         by skarma                        ###   #########       ###.error   */
/*                                                                            */
/* ************************************************************************** */

function	get_full_number(&$str1, &$str2)
{
	$nb = [0, 0];
	$c = -1;
	$tmp1 = explode('.', $str1);
	while (isset($tmp1[1][++$c]))
		$nb[0]++;
	$c = -1;
	$tmp2 = explode('.', $str2);
	while (isset($tmp2[1][++$c]))
		$nb[1]++;
	$str1 = implode("", $tmp1);
	$str2 = implode("", $tmp2);
	return ($nb);
}

function	slice_mul($result, $base, $signe, $nb, $operator)
{
	$decimal = $nb[0] + $nb[1];
	$c = $decimal;
	if ($decimal > strlen($result))
	{
		while (strlen($result) < $decimal)
			$result = $base[0] . $result;
	}
	if ($decimal === strlen($result))
		$result = $base[0]. '.' . $result;
	else
	{
		$tmp = "";
		$c = 0;
		$len = strlen($result);
		while ($c < $len)
		{
			if ($len - $c === $decimal)
				$tmp .= '.';
			$tmp .= $result[$c];
			$c++;
		}
		$result = $tmp;
	}
	if ($signe[0] === -1 || $signe[1] === -1)
		return ($operator[1] . $result);
	else
		return($result);
}
