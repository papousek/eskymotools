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
class Deleter extends AWorker implements IDeleter
{

	function delete(\eskymo\model\IEntity &$entity) {
		if ($entity->getState() !== IEntity::STATE_PERSISTED) {
			throw new \Nette\InvalidStateException("The given entity is in the state [".$entity->getState()."] instead of the expected state [".IEntity::STATE_PERSISTED."]");
		}
		$this->getConnection()
				->delete($this->getTableName())
				// FIXME: ID data type is hard coded
				->where("[".$entity->getIdName()."] = %i", $entity->getId());
	}

}
