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

class CallbackListener implements IListener
{

	private $callback;

	/**
	 * It creates a new callback listener
	 *
	 * @param array $callback The callback
	 */
	public function  __construct(array $callback) {
		if (!is_callable($callback)) {
			throw new InvalidArgumentException("The callback is not callable!");
		}
		$this->callback = $callback;
	}

	public function listen(IEvent $event) {
		return call_user_func($this->callback, $event);
	}

}
