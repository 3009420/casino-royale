<?php

namespace App\Model\Games;

use Nette;

/**
 *
 *
 * @author Raito
 */
class Roulette extends \Nette\Object
{

    /** @var \Nette\Security\User */
    private $user;

    /** @var \App\Model\Player */
    private $player;

    /** @var Nette\Database\Context */
    private $database;

    /** @var mixed */
    private $boxmatch;

    /** @var mixed */
    private $gamedata;

    /**
     *
     * @param Nette\Database\Context $database
     */
    public function __construct($gamedata, $boxmatch, \Nette\Security\User $user, Nette\Database\Context $database, \App\Model\Player $player)
    {
        $this->user = $user;
        $this->player = $player;
        $this->database = $database;
        $this->boxmatch = $boxmatch;
        $this->gamedata = $gamedata;
    }

    public function placeBet($amount, $value)
    {
        $this->player->addBet($this->gamedata['id'], $amount, $value);
    }

    public function getVersion()
    {
        return $this->gamedata['version'];
    }

}
