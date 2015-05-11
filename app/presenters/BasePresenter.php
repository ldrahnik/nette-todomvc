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

use Nette;


/**
 * Class BasePresenter
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var \ViewKeeper\ViewKeeper @inject */
    public $viewKeeper;

	/** @var \WebLoader\Nette\LoaderFactory @inject */
	public $webLoader;

    public function formatLayoutTemplateFiles()
    {
        return array($this->viewKeeper->getView($this->name, 'layouts'));
    }

    public function formatTemplateFiles()
    {
        return array($this->viewKeeper->getView($this->name, 'presenters', $this->action));
    }

	protected function createComponentCss()
	{
		return $this->webLoader->createCssLoader('default');
	}

    protected function createComponentPreJs()
    {
        return $this->webLoader->createJavaScriptLoader('pre');
    }

	protected function createComponentPostJs()
	{
		return $this->webLoader->createJavaScriptLoader('post');
	}
}
