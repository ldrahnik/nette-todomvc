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
use Doctrine;
use Nette\Security\Passwords;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends Kdyby\Doctrine\Entities\BaseEntity
{
    use Kdyby\Doctrine\Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(type="string", unique=true)
     * @var string
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string")
     * @var string
	 */
	protected $password;

	/**
	 * @ORM\OneToMany(targetEntity="App\Model\Task", mappedBy="user", cascade={"remove"})
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $tasks;

    /**
     * @param $username
     * @param $password
     */
	public function __construct($username, $password)
	{
        $this->username = $username;
        $this->password = Passwords::hash($password);

		$this->tasks = new Doctrine\Common\Collections\ArrayCollection();
	}
}