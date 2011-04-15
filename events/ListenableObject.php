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

namespace eskymo\events;

use eskymo;

abstract class ListenableObject extends Object implements IListenable
{

	/** @var array */
	private $listeners = array();

	/**
	 * It adds a new listner which listens to the specified event type
	 *
	 * @param string $type Event type
	 * @param IListener $listener
	 */
	protected final function addListener($type, IListener &$listener) {
		if (empty($type)) {
			throw new \InvalidArgumentException("type");
		}
		if (!isset($this->listeners[$type])) {
			$this->listeners[$type] = array();
		}
		$this->listeners[$type][] = $listener;
	}

	/**
	 * It calls all listeners which listen to the specified
	 */
	protected final function callListeners($type, IEvent &$event) {
		if (empty($type)) {
			throw new \InvalidArgumentException("type");
		}
		if (empty($this->listeners[$type])) {
			return;
		}
		foreach($this->listeners[$type] AS $listener) {
			$listener->listen($event);
		}
	}

}
