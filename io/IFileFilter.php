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
namespace eskymo\io;

use eskymo;

/**
 * This interface is designed to be implementde by file filters.
 *
 * @author      Jan Papousek
 * @see         FileTypeFilter
 * @see         FileNameFilter
 */
interface IFileFilter
{

	/**
	 * It checks if the file is accepted.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	function accepts(File $file);

}
