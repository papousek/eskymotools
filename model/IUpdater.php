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
interface IUpdater
{

	/**
	 * @param \eskymo\model\IEntity
	 * @return boolean
	 * @throws \InvalidStateException if the $entity has no ID
	 */
	function update(IEntity &$entity);

}
