<?php

// Numa v0.1
// 2022-05-20
// by @aaviator42

namespace Numa;

function gen_1($size, $start = 0, $step = 1){
	$arr = array();
	
	$value = (float)$start;
	for($i = 0; $i < $size; $i++){
		$arr[] = $value;
		$value += $step;
	}
	return $arr;
}
	
function gen_2($start, $end, $step = 1){
	$arr = array();
	
	$value = (float)$start;
	for($i = 0; $value <= $end; $i++){
		$arr[] = $value;
		$value += $step;
	}
	
	return $arr;
}
	
function gen_3($size, $value){
	$arr = array();
	
	for($i = 0; $i < $size; $i++){
		$arr[] = $value;
	}
	
	return $arr;
}

function gen_4($size, $min = 0, $max = 100, $step = 1){
	
	
	if($size * $step > $max - $min){
		$max *= ($size * $step);
	}

	
	var_dump($max);
	var_dump($min);
	$arr = range($min, $max, $step);
	
	shuffle($arr);
	
	$arr = array_slice($arr, 0, $size);
	
	return $arr;
}


function operate($array1, $operator, $array2){
	if(is_array($array2)){
		$array1 = array_values($array1);
		$array2 = array_values($array2);
		
		if(sizeof($array1) < sizeof($array2)){
			$until = sizeof($array1);
		} else {
			$until = sizeof($array2);
		}
		
		switch($operator){
			case "+":
				for($i = 0; $i < $until; $i++){
					$array1[$i] += $array2[$i];
				}
				break;
			
			case "-":
				for($i = 0; $i < $until; $i++){
					$array1[$i] -= $array2[$i];
				}
				break;
			
			case "*":
				for($i = 0; $i < $until; $i++){
					$array1[$i] *= $array2[$i];
				}
				break;
			
			case "/":
				for($i = 0; $i < $until; $i++){
					if($array2[$i] != 0){
						$array1[$i] /= $array2[$i];
					} else {
						$array1[$i] = NULL;
					}
				}
				break;
			
			case "%":
				for($i = 0; $i < $until; $i++){
					if((int)$array2[$i] != 0){
						$array1[$i] = (int)$array1[$i] % (int)$array2[$i];
					} else {
						$array1[$i] = NULL;
					}
				}
				break;
			
			case "**":
				for($i = 0; $i < $until; $i++){
					$array1[$i] = $array1[$i] ** $array2[$i];
				}
				break;
			
			default:
				throw new Exception("Numa [operate_array()]: Invalid  operator: [$operator]. ", 1);
		}
		
		return $array1;
		
	} else {
		$array1 = array_values($array1);
		$value = $array2;	
		$until = sizeof($array1);
		
		
		switch($operator){
			case "+":
				for($i = 0; $i < $until; $i++){
					$array1[$i] += $value;
				}
				break;
			
			case "-":
				for($i = 0; $i < $until; $i++){
					$array1[$i] -= $value;
				}
				break;
			
			case "*":
				for($i = 0; $i < $until; $i++){
					$array1[$i] *= $value;
				}
				break;
			
			case "/":
				for($i = 0; $i < $until; $i++){
					if($value != 0){
						$array1[$i] /= $value;
					} else {
						$array1[$i] = NULL;
					}
				}
				break;
			
			case "%":
				for($i = 0; $i < $until; $i++){
					if((int)$value != 0){
						$array1[$i] = (int)$array1[$i] % (int)$value;
					} else {
						$array1[$i] = NULL;
					}
				}
				break;
			
			case "**":
				for($i = 0; $i < $until; $i++){
					$array1[$i] = $array1[$i] ** $value;
				}
				break;
			
			default:
				throw new Exception("Numa [operate_value()]: Invalid  operator: [$operator]. ", 1);
		}
		
		return $array1;
	}
}

function precision($array1, $precision){
	$array1 = array_values($array1);
	$until = sizeof($array1);
	
	if($precision <0){
		$precision = 0;
	}	
	
	$p = 10 ** (int)$precision;
	
	for($i = 0; $i < $until; $i++){
		$array1[$i]  = intval($array1[$i] * $p) / $p;
	}
	
	return $array1;
}

function round($array1, $precision, $mode = PHP_ROUND_HALF_UP){

	$array1 = array_values($array1);
	
	$until = sizeof($array1);
		
	for($i = 0; $i < $until; $i++){
		$array1[$i]  = \round($array1[$i], (int) $precision, $mode);
	}
	
	return $array1;
}

function cast($array1, $replaceNULL = 0){
	$array1 = array_values($array1);
	
	$until = sizeof($array1);
	
	for($i = 0; $i < $until; $i++){
		if($replaceNULL){
			$array1[$i] = (float)$array1[$i];
		} else {
			if($array1[$i] != NULL){
				$array1[$i] = (float)$array1[$i];
			}
		}
			
	}
	
	return $array1;
}

function delete_elements($array1, $value = NULL, $all = 0){	
	
	if($value == NULL){
		return array_values(array_unique($array1, SORT_NUMERIC));
	}
	
	
	if($all || is_array($value)){
		if(is_array($value)){
			$array2 = $value;
		} else {
			$array2[] = $value;
		}
		$array1 = array_diff($array1, $array2);
	} else {
		$key = array_search($value, $array1);
		if($key !== false){
			unset($array1[$key]);
		}
	}
	return array_values($array1);
}