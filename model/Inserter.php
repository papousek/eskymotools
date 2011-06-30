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
 * @author		Jan Papousek
 */
class Inserter extends AWorker implements IInserter
{

	public function insert(IEntity &$entity) {
		if ($entity->getState() !== IEntity::STATE_NEW) {
			throw new \InvalidArgumentException("The entity can not be inserted because it is not in state [".IEntity::STATE_NEW."].");
		}
		// get data to insert the entity
		$data	= $entity->getData(IEntity::DATA_MODIFIED);
		$insert = array();
		foreach($this->getAvailableColumns() AS $column) {
			if (isset($data[$column->name])) {
				$insert[$column->name] = $data[$column->name];
			}
		}
		// set inserted column
		if (array_key_exists("inserted", $this->getAvailableColumns()) && !array_key_exists("inserted", $data)) {
			$insert["inserted"] = new \DateTime();
		}
		// check required columns
		foreach($this->getRequiredColumns() AS $column) {
			if (empty($insert[$column->getName()])) {
				throw new \InvalidArgumentException("The value for column [".$column->getName()."] is empty.");
			}
		}
		// execute insertion
		try {
			return $this->getConnection()->insert($this->getTableName(), $insert)->execute(TRUE);
		}
		catch(DibiDriverException $e) {
			switch($e->getCode()) {
				// duplicity
				case 1062:
					return -1;
					break;
				// otherwise
				default:
					throw $e;
					break;
			}
		}
		catch(DibiException $e) {
			throw $e;
		}
	}

}
