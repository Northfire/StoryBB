<?php
/**
 * String Helpers for StoryBB
 *
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2017 StoryBB and individual contributors (see contributors.txt)
 * @license 3-clause BSD (see accompanying LICENSE file)
 *
 * @version 3.0 Alpha 1
 */

/**
 * Given an array of strings, combine them with commas, e.g. "X, Y, Z".
 *
 * @param $array Array of strings to implode with commas
 * @return string Combined string
 */
function implode_comma($array)
{
	return implode(', ', $array);
}

/**
 * Given an array of strings, combine them in a 'more readable' way,
 * e.g. "X and Y", "X, Y and Z"
 *
 * @param $array Array of strings to nicely implode
 * @return string Combined string
 */
function implode_and($array)
{
	global $txt;
	loadLanguage('Who');

	if (count($array) <= 2)
	{
		return implode(' ' . $txt['credits_and'] . ' ', $array);
	}
	else
	{
		$last = array_pop($array);
		// And this should have an Oxford comma because @RaceProUK said so.
		return implode(', ', $array) . ', ' . $txt['credits_and'] . ' ' . $last;
	}
}

/**
 * Output a given string as JSON.
 *
 * @param mixed $data Data to export as JSON
 * @return string JSON-encoded data
 */
function stringhelper_json($data)
{
	return json_encode($data);
}

?>