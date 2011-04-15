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
interface IEntity extends \eskymo\IObject
{

	/**
	 * All modifed data
	 */
	const DATA_MODIFIED		= "modified";

	/**
	 * Data whis is not modified
	 */
	const DATA_NOT_MODIFIED	= "not_modified";

	/**
	 * All data
	 */
	const DATA_ALL			= "all";

	/**
	 * The entity is new and not persisted by the inseter.
	 */
	const STATE_NEW			= "new";

	/**
	 * The entity has been already persisted, but a column has been changed.
	 */
	const STATE_MODIFIED	= "modified";

	/**
	 * The entity is persisted and the attributes and columns in database  are the same.
	 */
	const STATE_PERSISTED	= "persisted";

	/**
	 * The entity is deleted
	 */
	const STATE_DELETED		= "deleted";

	/**
	 * It deletes the entity
	 */
	function delete();

	/**
	 * It returns data of the entity. The modifier says if we want to get modified,
	 * not modified or all data.
	 *
	 * @param string $modifier
	 * @return array
	 */
	function getData($modifier = self::DATA_ALL);

	/**
	 * It returns the entity ID
	 * 
	 * @return mixed
	 */
	function getId();

	/**
	 * It retuturns the entity state
	 *
	 * @return string
	 */
	function getState();

	/**
	 * It loads the data from DibiRow
	 *
	 * WARNING: It deletes old data!
	 * @param array Source data
	 * @return \eskymo\model\IEntity This method is fluent.
	 */
	function loadDataFromArray(array $resource);

	/**
	 * It persists the entity.
	 *
	 * @return \eskymo\model\IEntity
	 */
	function persist();

}
