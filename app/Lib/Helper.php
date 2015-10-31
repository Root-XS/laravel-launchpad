<?php

namespace Xs;

use App;

/**
 * Helper class
 *
 * Provides helper functions for general use.
 */
class Helper {

	/**
	 * Convert a boolean to "yes" or "no"
	 *
	 * @param bool|int $mBool
	 * @param bool $bUcfirst
	 * @return string
	 */
	public static function boolToAnswer($mBool, $bUcfirst = false)
	{
		$strAnswer = ($mBool && '0' !== $mBool) ? 'yes' : 'no';
		return $bUcfirst ? ucfirst($strAnswer) : $strAnswer;
	}

	/**
	 * Convert a "yes" or "no" to boolean.
	 *
	 * @param string $strAnswer
	 * @param bool $bNumber
	 * @return bool|int
	 */
	public static function answerToBool($strAnswer, $bNumber = false)
	{
		$bBool = (strtolower($strAnswer) === 'yes');
		return $bNumber ? (int) $bBool : $bBool;
	}

	/**
	 * Convert CamelCase to dashed-case.
	 *
	 * @param string
	 * @return string
	 */
	public static function camelToDashed($strValue)
	{
		return strtolower(preg_replace('/([a-zA-Z0-9])(?=[A-Z])/', '$1-', $strValue));
	}

	/**
	 * Convert an integer to its ordinal form (1st, 2nd, etc.)
	 *
	 * @todo As of 12/2/14 this is not in use. Keeping this Helper class as a placeholder.
	 *
	 * @see http://stackoverflow.com/questions/3109978/php-display-number-with-ordinal-suffix#answer-3110033
	 *
	 * @param int $i
	 * @return string
	 */
	public static function intToOrdinal($i)
	{
		$aSuffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
		if (($i%100) >= 11 && ($i%100) <= 13)
		   $strOrdinal = $i . 'th';
		else
		   $strOrdinal = $i . $aSuffixes[$i % 10];
		return $strOrdinal;
	}

	/**
	 * Generate a list (array) of n numbers.
	 *
	 * @param int $iLimit
	 * @return array
	 */
	public static function numberList($iLimit = 10)
	{
		if ($iLimit < 2)
			App::abort(500, 'Dude, that doesn\'t even make sense.');
		$aNumbers = [];
		for ($i=1; $i<$iLimit; $i++)
			$aNumbers[$i] = $i;
		return $aNumbers;
	}

}
