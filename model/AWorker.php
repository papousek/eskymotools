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
abstract class AWorker
{

	/**
	 * Names of the available columns of the table which is represeted by this model.
	 *
	 * @var array
	 */
	private $available;

	/** @var \DibiConnection */
	private $connection;

	/**
	 * Names of the required columns of the table which is represeted by this model.
	 *
	 * @var array
	 */
	private $required;

	/**
	 * Table which the worker works with
	 *
	 * @var string
	 */
	private $table;

	/**
	 * The info about this MySQL table
	 *
	 * @var \DibiTableInfo
	 */
	private $tableInfo;

	/**
	 * This attribute contains relationship between MySQL data native types
	 * and dibi modifier types.
	 *
	 * @var array
	 */
	// TODO: More types
	private static $types = array(
		"int"		=> "%i",
		"text"		=> "%s",
		"varchar"	=> "%s",
		"date"		=> "%d",
		"enum"		=> "%s"
	);


	/**
	 * It creates a new instance
	 *
	 * @param
	 * @param string $table
	 */
	public function  __construct(\DibiConnection $connection, $table) {
		$this->table		= $table;
		$this->connection	= $connection;
	}

	// ----- PROTECTED METHODS

	/**
	 * It returns all available columns
	 *
	 * @return array Names of required columns
	 */
	protected final function getAvailableColumns() {
		if (empty($this->available)) {
			$this->available = array();
			$columns = $this->getTableInfo()->getColumns();
			foreach ($columns AS $column) {
				$this->available[$column->getName()] = $column;
			}
		}
		return $this->available;
	}

	/**
	 * It returns a name of column which represents an identificator
	 * of the entity.
	 *
	 * @return \DibiColumnInfo The identificator column.
	 */
	protected function getIdColumn() {
		if (empty($this->identificator)) {
			$primaries = $this->getTableInfo()
				->getPrimaryKey();
			if (empty($primaries)) {
				throw new \Nette\NotSupportedException();
			}
			$this->identificator = \eskymo\tools\Arrays::firstValue($primaries->getColumns());
		}
		return $this->identificator;
	}

	/**
	 * It returns names of all required columns of MySQl table which the model
	 * work with.
	 *
	 * @return array Names of required columns
	 */
	protected final function getRequiredColumns() {
		if (empty($this->required)) {
			$this->required = array();
			$columns = $this->getAvailableColumns();
			foreach ($columns AS $column) {
				if (!$column->isNullable() && $column->getName() !== $this->getIdColumn()->getName() && strtolower($column->getNativeType()) !== 'timestamp') {
					$this->required[$column->getName()] = $column;
				}
			}
		}
		return $this->required;
	}

	/**
	 * It returns a table info.
	 *
	 * @return \DibiTableInfo
	 * @throws \DibiDriverException if there is a problem to work with database.
	 */
	protected final function getTableInfo() {
		if (empty($this->tableInfo)) {
			$this->tableInfo = $this->getConnection()->getDatabaseInfo()->getTable($this->getTableName());
		}
		return $this->tableInfo;
	}

	protected final function getTableName() {
		return $this->table;
	}

	protected final function getType(\DibiColumnInfo $column) {
		return \eskymo\tools\Arrays::get(self::$types, $column->getNativeType());
	}

	/** @return \DibiConnection */
	protected final function getConnection() {
		return $this->connection;
	}

}
