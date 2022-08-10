# Numa

Current version: `0.1` | `2022-05-20`  
License: `AGPLv3`

Note: Numa has been updated with a bunch of new functions, but they haven't been documented below yet. Take a look at `Numa.php`, they're pretty self explanatory.

## What is this? 

Numa (pronounced "new ma") contains a bunch of handy PHP functions for dealing with **num**erical **a**rrays. 

Numa expects all arrays to be numerical, with continuous keys starting from 0.

Arrays returned by Numa always have continuous keys, starting from 0.

> Be warned that currently Numa is very much a work in progress, and future versions may introduce breaking changes.


## Usage 

All functions are included in the namespace `Numa`. They each return a processed array.

Usage is as follows:

```php
<?php

require 'Numa.php';

$myArray = \Numa\gen_1(10, 5, 1); //[10, 11, 12, 13, 14]

```


## Functions

### 1. `gen_1($size, $start = 0, $step = 1)`

Returns an array with `$size` elements, at `$step` increments, beginning with `$start`.

```php
$myArray = \Numa\gen_1(5, 0); 		//[0, 1, 2, 3, 4]
$myArray = \Numa\gen_1(5, 10, 2); 	//[10, 12, 14, 16, 18]
```

Be aware of how [floating point math works](https://stackoverflow.com/questions/588004/is-floating-point-math-broken).  
For example, the output of the following might come as a surprise:

```php
$myArray1 = \Numa\gen_1(5, 0, 0.1); //[0, 0.1, 0.2, 0.30000000000000004, 0.4]

```

To deal with this, see the `precision()` and `round()` functions below.

### 2. `gen_2($start, $end, $step = 1)`

Returns an array with elements going up from `$start`,  to `$end`, at `$step` increments,.

```php
$myArray = \Numa\gen_2(0, 5); 		//[0, 1, 2, 3, 4]
$myArray = \Numa\gen_2(10, 20, 3); 	//[10, 13, 16, 19]
```

### 3. `gen_3($size, $value)`

Returns an array that contains `$size` elements, each of which is `$value`.

```php
$myArray = \Numa\gen_3(5, 5); 		//[5, 5, 5, 5, 5]
$myArray = \Numa\gen_3(4, 12); 		//[12, 12, 12, 12]
```

### 4. `gen_4($size, $min = 0, $max = 100, $step = 1)`

Returns an array that contains `$size` elements, each of which has a unique value between `$min` and `$max`, with the minimum difference between two elements being `$step`.

If `$size` is too big to allow for all values of the resulting array to be unique, `$max` is automatically increased to `$max * $size * $step`.

```php
$myArray = \Numa\gen_4(5); 		//five values between 0 and 100
$myArray = \Numa\gen_4(5, 0, 100, 2); 	//five values between 0 and 100, separated by at least 2
$myArray = \Numa\gen_4(200, 0, 100); 	//200 values between 0 and 20000
$myArray = \Numa\gen_4(200, -100); 	//200 values between -100 and 100
```

### 5. `operate($array1, $operator, $array2)`

Operate on each element of `$array1` with the corresponding element (if any) of `$array2`. If a single value is passed instead of `$array2`, then every element of `$array1` is operated on by the passed value.

Valid operators are:

Operator | Meaning
---------|-------------
`+`      | Addition
`-`      | Subtraction
`*`      | Multiplication
`/`      | Division
`%`      | Modulus
`**`     | Exponentiation

Values are cast to integers for modulo operations.  
Elements in the resulting array are set to NULL in case of modulus or division by 0.


```php
$myArray1 = [1, 2, 3, 4];
$myArray2 = [5, 6, 7, 8];

$result = \Numa\operate($myArray1, "+", 1); 		//[2, 3, 4, 5]
$result = \Numa\operate($myArray1, "**", 2); 		//[1, 4, 9, 16]
$result = \Numa\operate($myArray2, "-", $myArray1); 	//[4, 4, 4, 4]
$result = \Numa\operate($myArray2, "**", $myArray1);     //[5, 36, 343, 4096]
$result = \Numa\operate($myArray2, "%", $myArray1); 	//[0, 0, 1, 0]
```

### 6. `precision($array1, $precision)`

Sets precision for all elements in the array to the specified number of decimal places. Does _not_ round values up or down.

```php
$myArray1 = \Numa\gen_1(5, 0, 0.1);        //[0, 0.1, 0.2, 0.30000000000000004, 0.4]
$myArray1 = \Numa\precision($myArray1, 3); //[0, 0.1, 0.2, 0.3, 0.4]

$myArray1 = \Numa\gen_1(7, 0, 0.111);      //[0, 0.111, 0.222, 0.333, 0.444, 0.555, 0.666]
$myArray1 = \Numa\precision($myArray1, 1); //[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6]

```

### 7. `round($array1, $precision, $mode = PHP_ROUND_HALF_UP)`

Rounds all elements in the array using PHP's [`round()`](https://www.php.net/manual/en/function.round.php) function. 

If the `$precision` is positive, elements are rounded to precision significant digits after the decimal point.  
If the `$precision` is negative, elements are rounded to precision significant digits before the decimal point.

```php
$myArray1 = \Numa\gen_1(7, 0, 0.111);  //[0, 0.111, 0.222, 0.333, 0.444, 0.555, 0.666]
$myArray1 = \Numa\round($myArray1, 1); //[0, 0.1, 0.2, 0.3, 0.4, 0.6, 0.7]
```

Valid values of `$mode`:

Constant | Description
---------|-------------
`PHP_ROUND_HALF_UP` | Rounds a value away from zero when it is half way there, making 1.5 into 2 and -1.5 into -2.
`PHP_ROUND_HALF_DOWN` | Rounds a value towards zero when it is half way there, making 1.5 into 1 and -1.5 into -1. 
`PHP_ROUND_HALF_EVEN` | Rounds a value towards the nearest even value when it is half way there, making both 1.5 and 2.5 into 2. 
`PHP_ROUND_HALF_ODD` | Rounds a value towards the nearest odd value when it is half way there, making 1.5 into 1 and 2.5 into 3.

### 8. `cast($array1, $replaceNULL = 0)`

Casts all values in the array to floats. Use this if you have arrays of mixed types that you want to work with using the functions of this library. 

If `$replaceNULL` is `1`, then `NULL` values are replaced with `0`. 

This function is also handy when you want to re-index an array with continuous keys starting at 0 (for example, if you've unset elements).

```php
$myArray1 = ["0", "1", NULL, 0, 1, 2, 3];
$myArray1 = \Numa\cast($myArray1); //[0, 1, NULL, 0, 1, 2, 3]
```

### 9. `delete_elements($array1, $value, $all = 0)`

If `$value` is an array, then removes all instances of elements from `$value` from `$array1`.  
If `$value` is a single value, then removes the first occurrence of `$value` from `$array1`.  
If `$value` is a single value, and `$all` is 1, then removes all occurrences of `$value` from `$array1`.  
If `$value` is not passed, then removes all duplicate elements from `$array1`.

```php
$myArray1 = [1, 1, 2, 2, 3, 4, 5];
$myArray2 = [1, 4];
$result = \Numa\delete_elements($myArray1, $myArray2); 	//[2, 2, 3, 5]
$result = \Numa\delete_elements($myArray1); 		//[1, 2, 3, 4, 5]
$result = \Numa\delete_elements($myArray1, 2); 		//[1, 1, 2, 3, 4, 5]
$result = \Numa\delete_elements($myArray1, 2, 1); 	//[1, 1, 3, 4, 5]
```


--------
Documentation updated: `2022-05-20`.
