<?php

// Numa v0.8-wip
// 2022-06-10
// by @aaviator42
// AGPLv3

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
	$end = (float)$end;
	for($i = 0; $value <= $end; $i++){
		$arr[] = $value;
		$value += $step;
	}
	
	return $arr;
}
	
function gen_3($size, $value){
	$arr = array();
	
	if(!is_array($value)){
		//single value
		for($i = 0; $i < $size; $i++){
			$arr[] = $value;
		}
	} else {
		//array of possible values
		$values = cast($value);
		$last = sizeof($values);
		
		for($i = 0; $i < $size; $i++){
			$randval = $values[rand(0, $last - 1)];
			
			if($randval !== NULL){
				$randval = (float)$randval;
			}
			
			$arr[] = $randval;
		}
		
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


function check($array1){
	if(!is_array($array1)){
		return 0;
	}
	if(\count($array1) != \count($array1, COUNT_RECURSIVE)){
		//array is multidimensional
		return 0;
	}
	
	$array1 = array_values($array1);
	
	$until = sizeof($array1);	
	for($i = 0; $i < $until; $i++){
		if(!(is_numeric($array1[$i]) || $array1[$i] === NULL)){
			//val is not null and not numeric
			return 0;
		}		
	}

	return 1;
}

function operate($array1, $operator, $array2, $invalid = NULL){
	if(is_array($array2)){
		if(!(check($array1) && check($array2))){
			throw new \Exception("Numa [operate()]: Invalid input.", 1);
		}
		
		$array1 = array_values($array1);
		$array2 = array_values($array2);
		
		if(sizeof($array1) == 0 || sizeof($array2) == 0){
			return $array1;
		}
		

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
						$array1[$i] = $invalid;
					}
				}
				break;
			
			case "%":
				for($i = 0; $i < $until; $i++){
					if((int)$array2[$i] != 0){
						$array1[$i] = (int)$array1[$i] % (int)$array2[$i];
					} else {
						$array1[$i] = $invalid;
					}
				}
				break;
			
			case "**":
				for($i = 0; $i < $until; $i++){
					$array1[$i] = $array1[$i] ** $array2[$i];
				}
				break;
			
			default:
				throw new \Exception("Numa [operate()]: Inavlid operator: [$operator].", 1);
		}
		
		return $array1;
		
	} else {
		
		if(!check($array1)){
			throw new \Exception("Numa [operate()]: Invalid input.", 1);
		}
		
		$value = $array2;	
		$until = sizeof($array1);
		
		if(sizeof($array1) == 0){
			return $array1;
		}
		
		$array1 = array_values($array1);
		
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
						$array1[$i] = $invalid;
					}
				}
				break;
			
			case "%":
				for($i = 0; $i < $until; $i++){
					if((int)$value != 0){
						$array1[$i] = (int)$array1[$i] % (int)$value;
					} else {
						$array1[$i] = $invalid;
					}
				}
				break;
			
			case "**":
				for($i = 0; $i < $until; $i++){
					$array1[$i] = $array1[$i] ** $value;
				}
				break;
			
			default:
				throw new \Exception("Numa [operate()]: Inavlid operator: [$operator].", 1);
		}
		
		return $array1;
	}
}

function precision($array1, $precision){
	
	if($precision < 0){
		$precision = 0;
	}
	
	$p = 10 ** (int)$precision;
	
	if(!is_array($array1)){
		//single value
		$value = $array1;
		if($value !== NULL){
			$value = intval((float)$value * $p) / $p;
		}
		return $value;
	}
	
	if(!check($array1)){
		throw new \Exception("Numa [precision()]: Invalid input.", 1);
	}
	
	$array1 = array_values($array1);
	$until = sizeof($array1);
	
	for($i = 0; $i < $until; $i++){
		if($array1[$i] !== NULL){
			$array1[$i]  = intval($array1[$i] * $p) / $p;
		}
	}
	
	return $array1;
}

function round($array1, $precision, $mode = PHP_ROUND_HALF_UP){
	
	if(!is_array($array1)){
		//single value
		$value = $array1;
		if($value !== NULL){
			$value = \round((float)$value, (int) $precision, $mode);
		}
		return $value;
	}
	
	if(!check($array1)){
		throw new \Exception("Numa [round()]: Invalid input.", 1);
	}
	
	$array1 = array_values($array1);
	
	$until = sizeof($array1);
		
	for($i = 0; $i < $until; $i++){
		if($array1[$i] !== NULL){
			$array1[$i]  = \round($array1[$i], (int) $precision, $mode);
		}
	}
	
	return $array1;
}

function cast($array1, $replaceNULL = NULL){
	
	if(!check($array1)){
		throw new \Exception("Numa [cast()]: Invalid input.", 1);
	}
	
	$array1 = array_values($array1);
	
	$until = sizeof($array1);
	
	for($i = 0; $i < $until; $i++){
		
		if($array1[$i] === NULL){
			if($replaceNULL !== NULL){
				$array1[$i] = (float)$replaceNULL;
			}
		} else {
			$array1[$i] = (float)$array1[$i]; 
		}
			
	}
	
	return $array1;
}

function delete($array1, $value, $all = 0){	
	
	if(!check($array1)){
		throw new \Exception("Numa [delete()]: Invalid input.", 1);
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

function union($array1, $array2){
	if(!(check($array1) && check($array2))){
		throw new \Exception("Numa [union()]: Invalid input.", 1);
	}
	
	return array_values(array_unique(array_merge($array1, $array2)));
}

function unique($array1){
	if(!check($array1)){
		throw new \Exception("Numa [unique()]: Invalid input.", 1);
	}
	
	return array_values(array_unique($array1));
}

function push($array1, $array2){
	if(!is_array($array2)){
		$array2 = array($array2);
	}
	if(!(check($array1) && check($array2))){
		throw new \Exception("Numa [push()]: Invalid input.", 1);
	}
	return array_values(array_merge($array1, $array2));
}

function intersect($array1, $array2){
	if(!(check($array1) && check($array2))){
		throw new \Exception("Numa [intersect()]: Invalid input.", 1);
	}
	return array_values(array_unique(array_intersect($array1, $array2)));
}

function size($array1, $skipNULL = 0){
	if(!check($array1)){
		throw new \Exception("Numa [size()]: Invalid input.", 1);
	}
	
	if($skipNULL){
		$array1 = delete($array1, NULL, 1);
	}
	
	$array1 = array_values($array1);

	return sizeof($array1);
}

function hollow($array1, $skipNULL = 0){
	if(!check($array1)){
		throw new \Exception("Numa [hollow()]: Invalid input.", 1);
	}
	
	if($skipNULL){
		$array1 = delete($array1, NULL, 1);
	}
	
	$array1 = array_values($array1);
	
	$size = sizeof($array1);
	
	if($size == 0){
		return 1;
	} else {
		return 0;
	}
}

function format($array1, $readable = 0){
	if(!is_array($array1)){
		$value = $array1;
		if($value === NULL){
			$value = "NULL";
		}
		if($value === FALSE){
			$value = "FALSE";
		}
		return "{" . $value . "}";
	}

	$array1 = array_values($array1);
	$until = sizeof($array1);
	
	$str = "[";
	
	
	for($i = 0; $i < $until; $i++){
		//for every element in array

		if(is_array($array1[$i])){
			//element is itself an array!
			$str .= format($array1[$i]);
		} else {
			if($array1[$i] === NULL){
				$str .= 'NULL';
			} else {
				$str .= $array1[$i];
			}
		}
		
		if($i < $until - 1){
			$str .= ", ";
		}
	}
	
	$str .= "]";
	
	if($readable){
		$str2 = "";
		$level = 0;
		
		if(\count($array1) == \count($array1, COUNT_RECURSIVE)){
			return $str;
		}
		
		for($i = 0; $i < strlen($str); $i++){
			$char = $str[$i];
			if($char == "["){
				$str2 .= PHP_EOL;
				
				for($j = 0; $j < $level; $j++){
					$str2 .= "  ";
				}
				
				$str2 .= $char;
				$level++;
					
			} else if($char == "]"){
				$level--;
				if(!isset($str[$i+1])){
					$str2 .= PHP_EOL . $char;
					continue;
				}
				$str2 .= $char . ",";
				$str2 .= PHP_EOL;
			
				for($j = 0; $j < $level; $j++){
					$str2 .= "  ";
				}
				if($str[$i+1] == ','){
					$i++;
				}
				
			} else {
				$str2 .= $char;
			}
		}
		$str = $str2;
		$str = str_replace(PHP_EOL . "  " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "   " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "    " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "     " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "      " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "       " . PHP_EOL, PHP_EOL, $str);
		$str = str_replace(PHP_EOL . "         " . PHP_EOL, PHP_EOL, $str);
		
		$str = str_replace("  ],", "],", $str);
		$str = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $str);
	}
	
	return $str;
}

function replace($array1, $search, $replace, $all = 0){
	
	if(!check($array1)){
		throw new \Exception("Numa [replace()]: Invalid input.", 1);
	}
	
	if($replace !== NULL){
		$replace = (float)$replace;
	}
	
	$array1 = array_values($array1);
	
	$until = sizeof($array1);
		
	for($i = 0; $i < $until; $i++){
		if($array1[$i] === $search){
			$array1[$i] = $replace;
			if(!$all){
				break;
			}
		}
	}
	
	return $array1;
}

function search($array1, $search){
	if(!check($array1)){
		throw new \Exception("Numa [search()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	
	if($search !== NULL){
		$search = (float)$search;
	}
	
	$until = sizeof($array1);
		
	$result = array();
	
	for($i = 0; $i < $until; $i++){
		if($array1[$i] === $search){
			$result[] = $i;
		}
	}
	
	return $result;	
}

function index($array1, $search){
	if(!check($array1)){
		throw new \Exception("Numa [index()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	
	if($search !== NULL){
		$search = (float)$search;
	}
	
	$array1 = array_values($array1);
	
	$until = sizeof($array1);
	
	for($i = 0; $i < $until; $i++){
		if($array1[$i] === $search){
			return $i;
		}
	}

	return false;
}

function exists($array1, $search){
	if(!check($array1)){
		throw new \Exception("Numa [exists()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	
	if($search !== NULL){
		$search = (float)$search;
	}
	
	$until = sizeof($array1);
		
	$result = array();
	
	for($i = 0; $i < $until; $i++){
		if($array1[$i] === $search){
			return true;
		}
	}
	
	return false;	
}

function count($array1, $search){
	if(!check($array1)){
		throw new \Exception("Numa [count()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	if($search !== NULL){
		$search = (float)$search;
	}
	
	
	$until = sizeof($array1);
		
	$result = 0;
	
	for($i = 0; $i < $until; $i++){
		if($array1[$i] === $search){
			$result++;
		}
	}
	
	return $result;		
}

function filter($array1, $operator, $value, $return_i = 0, $invert = 0){
		
	if(!check($array1)){
		throw new \Exception("Numa [filter()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	
	//storage for output
	$o_values = array(); //values that match
	$o_indices = array();	//indices for values that match
	$o_values_i = array(); //values that don't match
	$o_indices_i = array(); //indices that don't match
	
	$until = sizeof($array1);
	
	if($value !== NULL){
		$value = (float) $value;
	}
	
	switch($operator){
		case "==":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] === $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;
		
		case "!=":
		case "<>":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] != $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;
		
		case "<":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] < $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;
		
		case ">":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] > $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;
		
		case "<=":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] <= $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;
		
		case ">=":
			for($i = 0; $i < $until; $i++){
				if($array1[$i] >= $value){
					$o_values[] = $array1[$i];
					$o_indices[] = $i;
				} else {
					$o_values_i[] = $array1[$i];
					$o_indices_i[] = $i;
				}
			}
			break;	

		default:
			throw new \Exception("Numa [filter()]: Inavlid operator: [$operator].", 1);
	}

	if($return_i == 0){
		if($invert){
			return $o_values_i;
		} else {
			return $o_values;
		}
	} else {
		if($invert){
			return $o_indices_i;
		} else {
			return $o_indices;
		}
	}
}

function mean($array1){
		
	if(!check($array1) || sizeof($array1) == 0){
		throw new \Exception("Numa [mean()]: Invalid input.", 1);
	}
	
	$array1 = delete($array1, NULL, 1);
	
	$array1 = array_values($array1);
	
	if(sizeof($array1) == 0){
		return NULL;
	}
	
	return array_sum($array1)/sizeof($array1);

}

function mode($array1){
		
	if(!check($array1) || sizeof($array1) == 0){
		throw new \Exception("Numa [mode()]: Invalid input.", 1);
	}

	$array1 = array_values($array1);
	
	for($i = 0; $i < sizeof($array1); $i++){
		if($array1[$i] === NULL){
			$array1[$i] = "NULL";
		}
	}

	
	
	$values = array_count_values($array1); 
	$mode = array_search(max($values), $values);
	
	if($mode === "NULL"){
		return NULL;
	} else {
		return $mode;
	}

}

function median($array1){	
	if(!check($array1)  || sizeof($array1) == 0){
		throw new \Exception("Numa [median()]: Invalid input.", 1);
	}
	
	$array1 = array_values($array1);
	\sort($array1);
	$size = sizeof($array1);
	
	if(($size % 2) == 1){
		//odd number of elements
		$index = intval($size / 2);
		return $array1[$index];
	} else {
		//even number of elements
		
		$index1 = intval($size / 2) - 1;
		$index2 = intval($size / 2);
		
		if($array1[$index1] === $array1[$index2]){
			return $array1[$index1]; //important for when [NULL, NULL]
		}
		
		return ($array1[$index1] + $array1[$index2]) / 2;
	}
}

function sum($array1){
	if(!check($array1)){
		throw new \Exception("Numa [sum()]: Invalid input.", 1);
	}

	$array1 = delete($array1, NULL, 1);
	
	$array1 = array_values($array1);
	
	return array_sum($array1);

}

function sort($array1, $rev = 0){
	if(!check($array1)){
		throw new \Exception("Numa [sort()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	
	if($rev){
		\rsort($array1);
	} else {
		\sort($array1);
	}
	
	return $array1;
}

function min($array1){
	if(!check($array1) || sizeof($array1) == 0){
		throw new \Exception("Numa [min()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	return \min($array1);
}	

function max($array1){
	if(!check($array1) || sizeof($array1) == 0){
		throw new \Exception("Numa [max()]: Invalid input.", 1);
	}
	
	$array1 = cast($array1);
	return \max($array1);
}

function pop($array1, $index){
	if(!check($array1)){
		throw new \Exception("Numa [pop()]: Invalid input.", 1);
	}
	
	if(!is_array($index)){
		$index = array($index);
	}
	
	foreach($index as $i){
		unset($array1[$i]);
	}
	
	return cast($array1);
}

function insert($array1, $array2, $index){
	if(!is_array($array2)){
		$array2 = array($array2);
	}
	if(!(check($array1) && check($array2))){
		throw new \Exception("Numa [insert()]: Invalid input.", 1);
	}

	$index = (int)$index;
	if($index < 0){
		$index = 0;
	}

	
	$finalarr1 = $array1;
	$finalarr2 = $array1;
	
	array_splice($finalarr1, $index);
	array_splice($finalarr2, 0, $index);
	
	$finalarr = array_merge($finalarr1, $array2, $finalarr2);
	
	return cast($finalarr);
}

function assign($array1, $value, $index){
	if(!is_array($index)){
		$index = array($index);
	}
	
	if(!check($array1)){
		throw new \Exception("Numa [assign()]: Invalid input.", 1);
	}
	
	for($i = 0; $i < sizeof($index); $i++){
		$index[$i] = (int)$index[$i];
		if($index[$i] < 0){
			$index[$i] = 0;
		}
	}
	
	$index = array_unique($index);

	
	foreach($index as $i){
		$array1[$i] = $value;
	}
	
	return cast($array1);
}

function same($array1, $array2){
	if(!(check($array1) && check($array2))){
		throw new \Exception("Numa [operate()]: Invalid input.", 1);
	}
	$array1 = cast($array1);
	$array2 = cast($array2);
	
	if($array1 === $array2){
		return true;
	} else {
		return false;
	}
}

function reverse($array1){
	if(!check($array1)){
		throw new \Exception("Numa [reverse()]: Invalid input.", 1);
	}
	$array1 = cast($array1);
	return array_reverse($array1);
}

function splice($array1, $offset, $length){
	if(!check($array1)){
		throw new \Exception("Numa [splice()]: Invalid input.", 1);
	}
	$array1 = cast($array1);
	array_splice($array1, $offset, $length);
	return $array1;
}

function clear($array1 = NULL){
	return array();
}

function random($array1){
	if(!check($array1)){
		throw new \Exception("Numa [random()]: Invalid input.", 1);
	}
	$array1 = cast($array1);
	$value = $array1[rand(0, sizeof($array1) - 1)];
	return $value;
}