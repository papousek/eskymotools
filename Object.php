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
namespace eskymo;

require_once __DIR__ . '/IObject.php';

class Object extends \Nette\Object implements IObject
{
    public function equals(IObject &$object) {
		return ($this === $object);
	}
}
