<?php

$str = 'This is my {cool|lame} {text|paragraph|tonnage}';

var_dump(breakApart($str));

/**
 * Should return matrix of possibilities
 * @param  string $string string to break apart
 * @param  array $arrays parent possibilities
 * @return array         matrix of possibilities
 */
function breakApart($string, array $arrays = null)
{

	$start = strpos($string, '{');
	$end = strpos($string, '}');
	if ($start && $end) {
		$length = $end - $start - 1;

		$capture = substr($string, $start + 1, $length);

		// Capture "This is my "
		$before = substr($string, 0 , $start);
		$leftOver = substr($string, $end+1);
		$capturedPossibilites = possibilities($capture);
		$matrix = array();

		foreach ($capturedPossibilites as $possibility) {
			if ($leftOver) {
				$children = breakApart($leftOver);
				foreach ($children as $child) {
					$matrix[] = $before . $possibility . $child;
				}
			} else {
				$matrix[] = $before . $possibility;
			}
		}
		return $matrix;
	}
	return array($string);
}

function possibilities($string)
{
	$break = strpos($string, '|');

	// Check if we have reached the end?
	if (!$break) {
		return array($string);
	} else {
		$word = substr($string, 0, $break);
		$remaining = substr($string, $break + 1);
		return array_merge(possibilities($remaining), array($word));
	}
}