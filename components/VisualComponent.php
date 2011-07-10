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
namespace eskymo\components;

use eskymo;

abstract class VisualComponent extends \Nette\Application\UI\Control
{

	public function __construct(\Nette\ComponentModel\IComponent $parent, $name) {
		parent::__construct($parent, $name);
		$this->startUp();
	}

	public function render() {
		$this->beforeRender();
		return $this->getTemplate()->render();
	}

	protected function beforeRender() {}

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		$componentName = strtr($this->getReflection()->getName(), array("Component" => "", $this->getReflection()->getNamespaceName() . "\\" => ""));
		$template->setFile(
			$this->getDirectory() . '/' .
			eskymo\tools\Strings::lowerFirst($componentName) . '.latte'
		);
		return $template;
	}

	protected function getDirectory() {
		return dirname($this->getReflection()->getFileName());
	}

	protected function startUp() {}

}
