<?php

namespace App\Model;

use Nette,
    Nette\Utils\Strings,
    Nette\Security\Passwords;

/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Performs an authentication.
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        $row = $this->database->table('user')->where('email', $email)->fetch();

        if (!$row || !Passwords::verify($password, $row['password']))
        {
            throw new Nette\Security\AuthenticationException('Email or password is incorrect.', self::IDENTITY_NOT_FOUND);
        }
        elseif (Passwords::needsRehash($row['password']))
        {
            $row->update(array(
                'password' => Passwords::hash($password),
            ));
        }

        $arr = $row->toArray();
        $arr['bets'] = array();

        unset($arr['password']);
        return new Nette\Security\Identity($row->id, $row->user_role->name, $arr);
    }

    /**
     * Adds new user.
     * @param  string
     * @param  string
     * @return void
     */
    public function add($email, $password)
    {
        try
        {
            $this->database->table('user')->insert(array(
                'email'    => $email,
                'password' => Passwords::hash($password),
            ));
        }
        catch (Nette\Database\UniqueConstraintViolationException $e)
        {
            throw new DuplicateNameException;
        }
    }

}

class DuplicateNameException extends \Exception
{

}
