<?php
/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace App\Presenters;

/**
 * Class HomepagePresenter
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class HomepagePresenter extends BasePresenter
{

	/** @var \App\Controls\ITodoListFactory @inject */
	public $ITodoListFactory;

	/**
	 * @return \App\Controls\TodoList
	 */
	protected function createComponentTodoList()
	{
		return $this->ITodoListFactory->create();
	}
}
