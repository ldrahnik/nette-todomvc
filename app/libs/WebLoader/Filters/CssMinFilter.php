<?php
/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace App\WebLoader\Filters;

use CssMin;

/**
 * Class CssMinFilter
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class CssMinFilter
{
	/**
	 * Minify target code
	 * @param string $code
	 * @return string
	 */
	public function __invoke($code)
	{
		return CssMin::minify($code, array("remove-last-semicolon"));
	}
}