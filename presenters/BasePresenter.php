<?php
namespace eskymo\presenters;
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
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{

	public function createComponentFlashMessages($name) {
		return new \eskymo\components\FlashMessages($this, $name);
	}

}
