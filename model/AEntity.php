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
abstract class AEntity extends \eskymo\Object implements IEntity
{

	/** @var \eskymo\model\IDeleter */
	private $deleter;

	private $id;

	/** @var \eskymo\model\IInserter */
	private $inserter;

	private $data;

	private $modified = array();

	private $state;

	/** @var \eskymo\model\IUpdater */
	private $updater;

	public function __construct(IInserter $inserter, IUpdater $updater, IDeleter $deleter) {
		$this->state		= IEntity::STATE_NEW;
		$this->inserter		= $inserter;
		$this->updater		= $updater;
		$this->deleter		= $deleter;
	}

	public final function delete() {
		if ($this->getId() === null) {
			throw new \Nette\InvalidStateException("The entity is not ready to be deleted.");
		}
		$this->getDeleter()->delete($this);
		$this->setState(IEntity::STATE_DELETED);
	}

	public function getData($modifier = self::DATA_ALL) {
		switch($modifier) {
			case IEntity::DATA_ALL:
				return $this->data;
				break;
			case IEntity::DATA_MODIFIED:
				$data = array();
				foreach($this->data AS $key => $value) {
					if (isset($this->modified[$key])) $data[$key] = $value;
				}
				return $data;
				break;
			case IEntity::DATA_NOT_MODIFIED:
				$data = array();
				foreach($this->data AS $key => $value) {
					if (empty($this->modified[$key])) $data[$key] = $value;
				}
				return $data;
				break;
			default:
				throw new \Nette\NotSupportedException("The given modifier [" . $modifier . "] is not supported.");
		}
		
	}

	public function getId() {
		if ($this->getState() !== IEntity::STATE_NEW && !$this->hasId()) {
			throw new \InvalidStateException("The entity has no ID.");
		}
		return $this->id;
	}

	public function getState() {
		return $this->state;
	}

	public final function loadDataFromArray(array $source) {
		if ($this->getState() != IEntity::STATE_NEW) {
			throw new \Nette\InvalidStateException("The entity is not in state [NEW]. It can't be loaded from array.");
		}
		foreach($source AS $name => $value) {
			if ($this->isValidToWrite($name)) {
				$this->setData($name, $value);
			}
		}
		$id = $this->loadId($source);
		if ($id !== NULL) {
			$this->id = $id;
			$this->setState(IEntity::STATE_PERSISTED);
			$this->clearModifiedColumns();
		}
	}

	public final function persist() {
		switch($this->getState()) {
			case IEntity::STATE_NEW:
				$this->id = $this->getInserter()->insert($this);
				break;
			case IEntity::STATE_MODIFIED:
				$this->getUpdater()->update($this);
				break;
			case IEntity::STATE_PERSISTED:
				break;
			default:
				throw new \Nette\InvalidStateException("The entity can not be persisted.");
		}
		$this->setState(IEntity::STATE_PERSISTED);
		$this->clearModifiedColumns();
		return $this;
	}

	// ---- PROTECTED METHODS

	/** @return \eskymo\model\IDeleter */
	protected final function getDeleter() {
		return $this->deleter;
	}

	abstract protected function getIdName();

	/** @return \eskymo\model\IInserter */
	protected final function getInserter() {
		return $this->inserter;
	}

	/** @return \eskymo\model\IUpdater */
	protected final function getUpdater() {
		return $this->updater;
	}

	protected function hasId() {
		return isset($this->id);
	}

	/**
	 * @param string not-translated name of the attribute
	 */
	abstract protected function isValidToRead($attribute);

	/**
	 * @param string not-translated name of the attribute
	 */
	abstract protected function isValidToWrite($attribute);

	/**
	 * It returns ID value from the source array
	 * @param array
	 * @return ID value
	 */
	protected function loadId(array $source) {
		return isset($source[$this->getIdName()]) ? $source[$this->getIdName()] : NULL;
	}

	protected final function setData($name, $value) {
		if (!isset($this->data[$name]) || $this->data[$name] !== $value) {
			$this->data[$name] = $value;
			$this->addModified($name);
		}
	}

	protected final function setState($state) {
		if (empty($state)) {
			throw new \InvalidArgumentException("state");
		}
		$this->state = $state;
	}

	// ---- PRIVATE METHODS

	private function addModified($attribute) {
		$this->modified[$attribute] = TRUE;
		if ($this->getState() == IEntity::STATE_PERSISTED) {
			$this->setState(IEntity::STATE_MODIFIED);
		}
	}

	private function clearModifiedColumns() {
		$this->modified = array();
	}

	private function isModified($attribute) {
		return isset($this->modified[$attribute]);
	}

}