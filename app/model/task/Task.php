<?php
/**
 * This file is part of the ldrahnik\nette-todomvc.
 *
 * (c) Lukáš Drahník <ldrahnik@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace App\Model;

use Kdyby;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Task extends Kdyby\Doctrine\Entities\BaseEntity
{
    use Kdyby\Doctrine\Entities\Attributes\Identifier;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $message;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     * @var boolean
     */
    protected $status = false;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}