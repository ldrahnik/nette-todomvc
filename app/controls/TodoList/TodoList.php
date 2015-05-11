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

use App\Model\GetTasks;
use App\Model\Task;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Application\UI\Form;
use ViewKeeper\ViewKeeper;
use Nette\Http\Request;

/**
 * Interface ITodoListFactory
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
interface ITodoListFactory
{

    /**
     * @return TodoList
     */
    function create();
}

/**
 * Class TodoList
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class TodoList extends Nette\Application\UI\Control
{

    /** @var ViewKeeper */
    private $viewKeeper;

    /** @var EntityManager */
    private $em;

    /** @var \Kdyby\Doctrine\EntityRepository */
    private $taskRepository;

    /** @var Request */
    private $request;

    /**
     * Values:  all|active|done
     * @var string
     * @persistent
     */
    public $state = 'all';

    public function __construct(ViewKeeper $viewKeeper, EntityManager $em, Request $request)
    {
        $this->request = $request;
        $this->em = $em;
        $this->taskRepository = $this->em->getRepository('\App\Model\Task');
        $this->viewKeeper = $viewKeeper;
    }

    public function render()
    {
        $this->template->setFile($this->viewKeeper->getView('TodoList', 'controls'));
        $this->template->source = $this->prepareSourceData($this->state);
        $this->template->render();
    }

    /**
     * @param $state
     * @return array('tasks' => array,
     *               'allCount' => integer,
     *               'doneCount' => integer,
     *               'leftCount' => integer,
     *               'state' => string [all|active|done]);
     */
    private function prepareSourceData($state)
    {
        $source = [];
        $userId = $this->getPresenter()->getUser()->getId();

        // TASKS filtered by current state
        if ($state == 'all') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
                    ->byUser($userId)
            )->applySorting('e.posId ASC')->toArray();
        } elseif ($state == 'active') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
                    ->byState(false)
                    ->byUser($userId)
            )->applySorting('e.posId ASC')->toArray();
        } elseif ($state == 'done') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
                    ->byState()
                    ->byUser($userId)
            )->applySorting('e.posId ASC')->toArray();
        }

        // COUNT all, done, left
        $source['allCount'] = $this->taskRepository->fetch((new GetTasks)->byUser($userId))->count();
        $source['doneCount'] = $this->taskRepository->fetch((new GetTasks)->byState()->byUser($userId))->count();
        $source['leftCount'] = $source['allCount'] - $source['doneCount'];

        // STATE
        $source['state'] = $state;

        return $source;
    }

    protected function createComponentAddTask()
    {
        $form = new Form;

        $form->addText('content')
            ->setHtmlId('new-todo')
            ->setAttribute('placeholder', 'What needs to be done?')
            ->setAttribute('autofocus');

        $form->onSuccess[] = function ($form) {
            if ($form->values->content) {
                $userId = $this->getPresenter()->getUser()->getId();

                $task = new Task($this->em->getReference('\App\Model\User', $userId), $form->values->content);
                $task->posId = $this->taskRepository->fetch((new GetTasks)->byUser($userId))->count() + 1;

                $this->em->persist($task);
                $this->em->flush();
                $this->redrawControl('tasks');
            }
        };
        return $form;
    }

    public function handleRemoveTask()
    {
        $id = $this->request->getQuery('id');
        $task = $this->taskRepository->findOneBy(array('id' => $id));

        $this->em->remove($task);
        $this->em->flush();
        $this->redrawControl('tasks');
    }

    public function handleChangeTaskState()
    {
        $id = $this->request->getQuery('id');

        $task = $this->taskRepository->findOneBy(array('id' => $id));

        if ($task != null) {
            $task->isDone = !$task->isDone;
            $this->em->flush($task);
            $this->redrawControl('tasks');
        }
    }

    public function handleChangeTasksState()
    {
        $checked = $this->request->getQuery('checked');

        $checkedBoolean = $checked === 'true' ? true : false;

        foreach ($this->taskRepository->fetch((new GetTasks)->byState(!$checkedBoolean)) as $task) {
            $task->isDone = $checkedBoolean;
            $this->em->persist($task);
        }
        $this->em->flush();
        $this->redrawControl('tasks');
    }

    public function handleClearDoneTasks()
    {
        $source = $this->prepareSourceData('done');

        foreach ($source['tasks'] as $task) {
            $this->em->remove($task);
        }
        $this->em->flush();
        $this->redrawControl('tasks');
    }

    /**
     * @param $state
     */
    public function handleChangeTodoState($state)
    {
        $this->state = $state;
        $this->redrawControl('tasks');
    }

    public function handleSaveOrder()
    {
        $data = $this->request->getQuery('data');

        $posId = 1;
        foreach ($data as $node) {
            $task = $this->taskRepository->findOneBy(array('id' => $node));

            if ($task->posId != $posId) {
                $task->posId = $posId;
                $this->em->persist($task);
            }
            $posId++;
        }
        $this->em->flush();
    }

    public function handleEditTask()
    {
        $id = $this->request->getQuery('id');
        $value = $this->request->getQuery('value');

        $task = $this->taskRepository->find($id);

        if ($value) {
            $task->setContent($value);
            $this->em->persist($task);
        } else {
            $this->em->remove($task);
        }
        $this->em->flush();
        $this->redrawControl('tasks');
    }
}