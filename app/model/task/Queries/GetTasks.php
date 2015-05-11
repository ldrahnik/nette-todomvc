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
class GetTasks extends BaseQuery {


    /**
     * @param $isDone
     * @return $this
     */
    public function byState($isDone = true)
    {
        $this->filter[] = function (Kdyby\Doctrine\QueryBuilder $qb) use ($isDone) {
            $qb
                ->andWhere('e.isDone = :isDone')
                ->setParameter('isDone', $isDone);
        };
        return $this;
    }

    /**
     * @param $userId
     * @return $this
     */
    public function byUser($userId)
    {
        $this->filter[] = function (Kdyby\Doctrine\QueryBuilder $qb) use ($userId) {
            $qb
                ->leftJoin('e.user', 'user')
                ->andWhere('user.id = :userId')
                ->setParameter('userId', $userId);
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