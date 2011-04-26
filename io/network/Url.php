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
namespace eskymo\io\network;

/**
 * The network URL.
 *
 * @author      Jan Papousek
 */
class Url
{

	private $path;

	/** @var \eskymo\IEnvironment */
	private $environment;

	public function __construct($path) {
		if (empty($path)) {
			throw new \InvalidArgumentException("The parameter [path] is empty.");
		}
		$this->path = $path;
	}

	/** @return \eskymo\IEnvironment */
	public function getEnvironment() {
		if (isset($this->environment)) {
			return $this->environment;
		}
		else {
			return \eskymo\Environment::getEnvironment();
		}
	}

	public function getPath() {
		return $this->path;
	}

	public function setEnvironment(\eskymo\IEnvironment $environment) {
		$this->environment = $environment;
	}

	public function save(\eskymo\io\File $file) {
		if ($this->getEnvironment()->isLinux() && function_exists('exec')) {
			exec('wget ' . $this->getPath() . ' ' . $file->getPath());
		}
		else {
			file_put_contents($file->getPath() ,file_get_contents($this->getPath()));
		}
	}

}

