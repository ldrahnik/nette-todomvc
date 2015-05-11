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
 * Class SignPresenter
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class SignPresenter extends BasePresenter
{

    public function actionIn()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Homepage:default');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->redirect('Homepage:default');
    }
}

