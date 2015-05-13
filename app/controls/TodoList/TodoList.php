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
        if ($state == 'all') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
            )->toArray();
        } elseif ($state == 'active') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
                    ->byState(false)
            )->toArray();
        } elseif ($state == 'done') {
            $source['tasks'] = $this->taskRepository->fetch(
                (new GetTasks)
                    ->byState()
            )->toArray();
        }

        $source['allCount'] = $this->taskRepository->fetch((new GetTasks))->count();
        $source['doneCount'] = $this->taskRepository->fetch((new GetTasks)->byState())->count();
        $source['leftCount'] = $source['allCount'] - $source['doneCount'];
        $source['state'] = $state;

        return $source;
    }

    protected function createComponentAddTask()
    {
        $form = new Form;

        $form->addText('message')
            ->setHtmlId('new-todo')
            ->setAttribute('placeholder', 'What needs to be done?')
            ->setAttribute('autofocus');

        $form->onSuccess[] = function ($form) {
            if ($form->values->message) {

                $task = new Task($form->values->message);
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
        $status = $this->request->getQuery('status');
        $statusBoolean = $status === 'true' ? true : false;

        foreach ($this->taskRepository->fetch((new GetTasks)->byState(!$statusBoolean)) as $task) {
            $task->isDone = $statusBoolean;
            $this->em->persist($task);
        }
        $this->em->flush();
        $this->redrawControl('tasks');
    }

    public function handleClearDoneTasks()
    {
        $data = $this->prepareSourceData('done');

        foreach ($data['tasks'] as $task) {
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

    public function handleEditTask()
    {
        $id = $this->request->getQuery('id');
        $message = $this->request->getQuery('message');

        $task = $this->taskRepository->find($id);
        if ($message) {
            $task->setMessage($message);
            $this->em->persist($task);
        } else {
            $this->em->remove($task);
        }
        $this->em->flush();
        $this->redrawControl('tasks');
    }
}