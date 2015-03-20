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

    const
      TABLE_NAME = 'user',
      COLUMN_ID = 'id',
      COLUMN_EMAIL = 'email',
      COLUMN_PASSWORD_HASH = 'password',
      COLUMN_ROLE = 'role';

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

        $row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_EMAIL, $email)->fetch();

        if (!$row || !Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH]))
        {
            throw new Nette\Security\AuthenticationException('Email or password is incorrect.', self::IDENTITY_NOT_FOUND);
        }
        elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH]))
        {
            $row->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
            ));
        }

        $arr = $row->toArray();
        unset($arr[self::COLUMN_PASSWORD_HASH]);
        return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
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
            $this->database->table(self::TABLE_NAME)->insert(array(
                self::COLUMN_EMAIL         => $email,
                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
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
