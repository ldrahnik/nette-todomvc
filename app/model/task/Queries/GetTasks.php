<?php

namespace App\Model;

/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

use Kdyby;

/**
 * Class GetTasks
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class GetTasks extends BaseQuery
{


    /**
     * @param $status
     * @return $this
     */
    public function byState($status = true)
    {
        $this->filter[] = function (Kdyby\Doctrine\QueryBuilder $qb) use ($status) {
            $qb
                ->andWhere('e.status = :status')
                ->setParameter('status', $status);
        };
        return $this;
    }

    /**
     * @param \Kdyby\Doctrine\QueryBuilder $qb
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    protected function addBaseSelect(Kdyby\Doctrine\QueryBuilder $qb)
    {
        return $qb->select('e')->from('\App\Model\Task', 'e');
    }
}