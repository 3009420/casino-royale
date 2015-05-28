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
    private $boxdata;

    /** @var mixed */
    private $gamedata;

    /**
     *
     * @param Nette\Database\Context $database
     */
    public function __construct($gamedata, $boxdata, \Nette\Security\User $user, Nette\Database\Context $database, \App\Model\Player $player)
    {
        $this->user = $user;
        $this->player = $player;
        $this->database = $database;
        $this->boxdata = $boxdata;
        $this->gamedata = $gamedata;
    }

    public function placeBet($amount, $value)
    {
        $data = array(
            'value' => $value,
            'name'  => $this->boxdata[$value]['name'],
            'color' => $this->boxdata[$value]['color'],
            'ratio' => $this->boxdata[$value]['ratio']
        );
        return $this->player->addBet($this->gamedata['id'], $amount, $data);
    }

    public function getVersion()
    {
        return $this->gamedata['version'];
    }

    public function getGameId()
    {
        return $this->gamedata['id'];
    }

}
