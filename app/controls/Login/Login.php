<?php
/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace App\Controls;

use Nette;
use Nette\Application\UI\Form;
use ViewKeeper\ViewKeeper;

/**
 * Interface ILoginFactory
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
interface ILoginFactory
{

    /**
     * @return Login
     */
    function create();
}

/**
 * Class Login
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class Login extends Nette\Application\UI\Control
{

    /** @var ViewKeeper */
    private $viewKeeper;

    public function __construct(ViewKeeper $viewKeeper)
    {
        $this->viewKeeper = $viewKeeper;
    }

    public function render()
    {
        $this->template->setFile($this->viewKeeper->getView('Login', 'controls'));
        $this->template->render();
    }

    protected function createComponentLoginForm()
    {
        $form = new Form;

        $form->addText('username')
            ->setAttribute('placeholder', 'username')
            ->setAttribute('autofocus')
            ->setHtmlId('login-field');
        $form->addText('password')
            ->setAttribute('placeholder', '*****')
            ->setHtmlId('login-field');

        $form->addSubmit('submit')
            ->setHtmlId('login-field');

        $form->onSuccess[] = function ($form) {
            $values = $form->values;

            $this->getPresenter()->getUser()->setExpiration('120 minutes', TRUE);
            try {
                $this->getPresenter()->getUser()->login($values->username, $values->password);

                $this->redirect('this');
            } catch (Nette\Security\AuthenticationException $e) {
                $this->getPresenter()->flashMessage('Wrong username or password.', 'Error');
            }
        };

        return $form;
    }
}