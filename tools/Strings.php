<?php
/**
 * This source file is subject to the "New BSD License".
 *
 * For more information please see http://eskymo.zimodej.cz
 *
 * @copyright	Copyright (c) 2009	Jan Papoušek (jan.papousek@gmail.com),
 *									Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://eskymo.zimodej.cz
 */

namespace eskymo\tools;

class Strings
{

	/**
	 * It converts camel case string to undescore form.
	 *
	 * @param string ASCII string
	 * @return string
	 */
	public static function camel2underscore($string) {
		return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $string));
	}

	/**
	 * It converts undercase string to a camel case form.
	 *
	 * @param string ASCII string
	 * @return string
	 */
	public static function underscore2camel($string, $firstUpper = FALSE) {
		if ($firstUpper) {
			  $string[0] = strtoupper($string[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $string);
	}

	/**
	 * It checks if the string represents a datetime.
	 *
	 * @param string $datetime
	 * @return bool
	 */
	public static function isDateTime($datetime) {
		if (empty($datetime)) {
			throw new \eskymo\NullPointerException("datetime");
		}
		return ereg("^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$",$datetime);
	}

	/**
	 * It returns string similarity using Levenshtein distance.
	 *
	 * @return int
	 */
	public static function similarity($first, $second) {
		return levenshtein($first, $second);
	}

	/**
	 * The PHP function lcFirst is available in the PHP version >= 5.3.0,
	 * this method does the same but in the lower PHP versions.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function lowerFirst($string) {
		static $from	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		static $to		= "abcdefghijklmnopqrstuvwxyz";
		$first			= substr($string, 0, 1);
		$rest			= substr($string, 1, strlen($string) - 1);
		return strtr($first, $from, $to) . $rest;
	}

	/**
	 * It generates random string with specified length.
	 *
	 * @param int $length
	 * @return string
	 * @throws NullPointerException if the $lenght is empty.
	 * @throws InvalidArgumentException if the length is not positive number.
	 */
	public static function random($length) {
		if (empty ($length))  {
			throw new NullPointerException("length");
		}
		if ($length <= 0) {
			throw new InvalidArgumentException("length");
		}
		$chars = array_merge(range("a","z"), range("A","Z"), range(0,9));
		$result = "";
		for($i=0; $i<$length; $i++) {
			$result .= $chars[rand(0,count($chars)-1)];
		}
		return $result;
	}

	public function hardTruncate($s,$maxLen,$append = "\xE2\x80\xA6") {
		if($maxLen >= mb_strlen($s)) {
			return $s;
		} else {
			return mb_substr($s,0, $maxLen-1).$append;
		}
	}

}
