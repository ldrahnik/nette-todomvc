<?php
/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace App;

use Nette;
use    Nette\Application\Routers\RouteList;
use    Nette\Application\Routers\Route;

/**
 * Class RouterFactory
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class RouterFactory
{

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList();
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
    }
}
