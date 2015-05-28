<?php

namespace App\Model;

use Nette;

/**
 * God&dev gameplay mode
 */
class Devmod extends Nette\Object
{

    /** @var \Nette\Security\User */
    private $user;

    /** @var Nette\Database\Context */
    private $database;

    /**
     *
     * @param Nette\Database\Context $database
     */
    public function __construct(\Nette\Security\User $user, Nette\Database\Context $database)
    {
        $this->user = $user;
        $this->database = $database;
    }

    /**
     * Adds specified amount of credits to specified player or to player who requested, if second parameter is omitted
     * @param type $amount
     * @param type $userid
     */
    public function addCredits($amount, $userid = null)
    {
        $userid = $userid ? $userid : $this->user->id;
        $user = $this->database->table('user')->get($userid);
        $user->update(array('credits' => $amount));
    }

}
