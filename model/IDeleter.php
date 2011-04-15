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
interface IDeleter
{

    /**
     * @param \eskymo\mode\IEntity
	 * @return boolean
	 * @throws InvalidStateException if the record cannot be deleted
	 */
	function delete(\eskymo\model\IEntity &$entity);

}