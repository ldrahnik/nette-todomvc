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

use Kdyby\Doctrine\EntityManager;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Object;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;

/**
 * Class Authenticator
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class Authenticator extends Object implements IAuthenticator
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        $user = $this->em->getRepository('\App\Model\User')->findOneBy(array('username' => $username));

        if (!$user || !Passwords::verify($password, $user->password)) {
            throw new AuthenticationException("Wrong username or password.");
        }

        $data['username'] = $username;
        unset($data['password']);

        return new Identity($user->getId(), null, $data);
    }
}