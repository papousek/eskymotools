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
class Repository extends ARepository
{

	/** @var \DibiConnection */
	private $connection;

	private $name;

	private $namespace;

	/**
	 * It create a new instance of the repository
	 *
	 * @param string Entity name
	 * @param string Namespace of the classes
	 */	
	public function __construct(\DibiConnection $connection, $name, $namespace = '') {
		$this->name			= ucfirst($name);
		$this->namespace	= trim($namespace, '\\');
	}

	/** @return \eskymo\model\IEntity */
	public function createEmpty() {
		$entity = '\\' . $namespace . '\\' .$this->getName() . "Entity";
		return new $entity($this->getInserter(), $this->getUpdater(), $this->getDeleter());
	}

	// ------ PROTECTED METHODS

	/** @return \DibiConnection */
	protected final function getConnection() {
		return $this->connection;
	}

	/** @return \eskymo\model\IDeleter */
	protected function createDeleter() {
		$deleter = '\\' . $namespace . '\\' .$this->getName() . "Deleter";
		if (class_exists($deleter)) {
			return new $deleter($this->getConnection(), strtolower($this->getName()));
		}
		else {
			return new Deleter($this->getConnection(), strtolower($this->getName()));
		}
	}

	/** @return \eskymo\model\IDeleter */
	protected function createUpdater() {
		$updater = '\\' . $namespace . '\\' .$this->getName() . "Updater";
		if (class_exists($updater)) {
			return new $updater($this->getConnection(), strtolower($this->getName()));
		}
		else {
			return new Updater($this->getConnection(), strtolower($this->getName()));
		}
	}

	protected function getName(){
		return $this->name;
	}
}
