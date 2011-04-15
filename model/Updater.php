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
class Updater extends AWorker implements IUpdater
{

	/**
	 * Table which the updater works with
	 *
	 * @var string
	 */
	private $table;

	/** @var \DibiConnection */
	private $connection;

	/**
	 * It creates a new instance
	 *
	 * @param
	 * @param string $table
	 */
	public function  __construct(\DibiConnection $connection, $table) {
		$this->table = $table;
	}

	public function update(IEntity &$entity) {
		if ($entity->getState() !== IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [".IEntity::STATE_MODIFIED."].");
		}
		// get data to update the entity
		$data	= $entity->getData(IEntity::DATA_MODIFIED);
		$update = array();
		foreach($this->getAvailableColumns() AS $column) {
			if (isset($data[$column->name])) {
				$update[$column->name] = $data[$column->name];
			}
		}
		// check required columns
		foreach($this->getRequiredColumns() AS $column) {
			if (key_exists($column->getName()) && empty($update[$column->getName()])) {
				throw new \NullPointerException("The value for column [".$column->getName()."] is empty.");
			}
		}
		// execute update
		$modified = $this->getConnection()
				->update($this->getTableName(), $update)
				->where($this->getIdColumn() . ' = ' . $this->getType($this->getIdColumn()), $entity->getId())
				->execute(TRUE);
		return $modified !== 0;
	}

	// ----- PROTECTED METHODS

	protected function getTableName() {
		return $this->table;
	}

	/** @return \DibiConnection */
	protected function getConnection() {
		return $this->connection;
	}

}
