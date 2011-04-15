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
abstract class ARepository implements IRepository
{

	/** @var \eskymo\model\IDeleter */
	private $deleter;

	/** @var \eskymo\model\IInserter */
	private $inserter;

	/** @var \eskymo\model\IUpdater */
	private $updater;

	function fetchAndCreate(IDataSource $source) {
		$row = $source->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromArray($row->toArray(), "Load");
	}


	function fetchAndCreateAll(IDataSource $source) {
		$result = array();
		while($entity = $this->fetchAndCreate($source)){
			$result[] = $entity;
		}
		return $result;
	}

	// ------- PROTECTED METHODS

	/** @return \eskymo\model\IDeleter */
	abstract protected function createDeleter();

	/** @return \eskymo\model\IInserter */
	abstract protected function createInserter();

	/** @return \eskymo\model\IUpdater */
	abstract protected function createUpdater();

	/** @return \eskymo\model\IDeleter */
	protected final function getDeleter() {
		if (empty($this->deleter)) {
			$this->deleter = $this->createDeleter();
		}
		return $this->deleter;
	}

	/** @return \eskymo\model\IInserter */
	protected final function getInserter() {
		if (empty($this->inserter)) {
			$this->inserter = $this->createInserter();
		}
		return $this->inserter;
	}

	/** @return \eskymo\model\IUpdater */
	protected final function getUpdater() {
		if (empty($this->updater)) {
			$this->updater = $this->createUpdater();
		}
		return $this->updater;
	}

}
