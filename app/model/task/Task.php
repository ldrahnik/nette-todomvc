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
     * @ORM\Column(type="text", nullable=TRUE)
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     * @var boolean
     */
    protected $isDone = false;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     * @var integer
     */
    protected $posId;

    /**
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
}