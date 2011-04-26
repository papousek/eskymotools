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
namespace eskymo;

/**
 * The environment
 *
 * @author      Jan Papousek
 */
class Environment implements IEnvironment
{

	/** @var Environment */
	private static $environment;

	private $isLinux;

	private $isWindows;

	private function __construct() {}

	public function isLinux() {
		if (!isset($this->isLinux)) {
			$this->isLinux = strpos(strtolower(php_uname()), 'linux');
		}
		return $this->isLinux;
	}

	public function isWindows() {
		if (!isset($this->isWindows)) {
			$this->isWindows = (strtolower(substr(PHP_OS, 0, 3)) === 'win');
		}
		return $this->isWindows;
	}

	/** @return Environment */
	public static function getEnvironment() {
		if (empty(self::$environment)) {
			self::$environment = new Environment();
		}
		return self::$environment;
	}

}
