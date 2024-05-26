<?php

namespace App;

/**
 * @package App
 */
class Ai {


	/**
	 * Merges arrays with delta keys
	 */
	public static function processDeltas($chunks)
	{
		$combined = [];
		foreach ($chunks as $chunk) {
			$combined = Ai::recursiveMergeWithConcat($combined, $chunk['delta']);
		}
		return $combined;
	}

	public static function recursiveMergeWithConcat($array1, $array2)
	{
		foreach ($array2 as $key => $value) {
			// If the key exists in the first array
			if (isset($array1[$key])) {
				if (is_array($array1[$key]) && is_array($value)) {
					// Both values are arrays, recurse
					$array1[$key] = Ai::recursiveMergeWithConcat($array1[$key], $value);
				} elseif (is_string($array1[$key]) && is_string($value)) {
					// Both values are strings, concatenate
					$array1[$key] .= $value;
				} else {
					// If types don't match or are not both arrays/strings, replace the value
					$array1[$key] = $value;
				}
			} else {
				// Key does not exist in the first array, add it
				$array1[$key] = $value;
			}
		}
		return $array1;
	}
}