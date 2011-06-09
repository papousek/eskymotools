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

namespace eskymo\model;

/**
 * @author Jan Papousek
 */
abstract class Entity extends AEntity
{

	private static $readAtrributes = array();

	private static $writeAtrributes = array();

	private static $initalized = array();

	private static $idName = array();

	public function __construct(IInserter $inserter, IUpdater $updater, IDeleter $deleter) {
		parent::__construct($inserter, $updater, $deleter);
	}

	public function & __get($name) {
		$original = $name;
		$name = \eskymo\tools\Strings::camel2underscore($name);
		if ($this->isValidToRead($name)) {
			$data = $this->getData();
			return $data[$name];
		}
		else {
			throw new \Nette\MemberAccessException("The attribute [$original] is not available to read in the class [" . get_class($this) . "].");
		}
	}

	public function __set($name, $value) {
		$original = $name;
		$name = \eskymo\tools\Strings::camel2underscore($name);
		if ($this->isValidToWrite($name)) {
			$this->setData($name, $value);
		}
		else {
			throw new \Nette\MemberAccessException("The attribute [$original] is not available to write in the class [" . get_class($this) . "].");
		}
	}

	public function __isset($name) {
		$original = $name;
		$name = \eskymo\tools\Strings::camel2underscore($name);
		$data = $this->getData();
		return $this->isValidToRead($name) && isset($data[$name]);
	}

	// ----- PROTECTED METHODS

	protected function getIdName() {
		$this->init();
		return self::$idName[get_class($this)];
	}

	protected function isValidToRead($attribute) {
		$this->init();
		return isset(self::$readAtrributes[get_class($this)][$attribute]) || isset(self::$writeAtrributes[get_class($this)][$attribute]);
	}

	protected function isValidToWrite($attribute) {
		$this->init();
		return isset(self::$writeAtrributes[get_class($this)][$attribute]);
	}

	// ----- PRIVATE METHODS

	private function init() {
		if (isset(self::$initalized[get_class($this)])) return;

		$annotations = $this->getReflection()->getAnnotations();
		if (isset($annotations['property'])) {
			foreach($annotations['property'] AS $property) {
				self::$writeAtrributes[get_class($this)][$this->parseProperty($property)] = TRUE;
			}
		}
		if (isset($annotations['property-write'])) {
			foreach($annotations['property-write'] AS $property) {
				self::$writeAtrributes[get_class($this)][$this->parseProperty($property)] = TRUE;
			}
		}
		if (isset($annotations['property-read'])) {
			foreach($annotations['property-read'] AS $property) {
				self::$readAtrributes[get_class($this)][$this->parseProperty($property)] = TRUE;
			}
		}
		if (isset($annotations['id'])) {
			self::$idName[get_class($this)] = $annotations['id'];
		}
		else {
			preg_match("/\w+$/", get_class($this), $matches);
			self::$idName[get_class($this)] = 'id_' . preg_replace('/\_entity$/', "",\eskymo\tools\Strings::camel2underscore(\eskymo\tools\Arrays::firstValue($matches)));
		}

		self::$initalized[get_class($this)] = TRUE;
	}

	private function parseProperty($string) {
		preg_match("/\\\$\w+/", $string, $matches);
		return \eskymo\tools\Strings::camel2underscore(trim(\eskymo\tools\Arrays::firstValue($matches), '$'));
	}
}

