<?php

namespace App\Model;

use Nette;

/**
 * Global player class
 */
class Player extends Nette\Object
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
     * Loads specified data from DB.
     */
    public function loadPlayerData()
    {
        if (!$this->user->getIdentity())
        {
            return false;
        }

        $row = $this->database->table('user')->get($this->user->id);
        $this->user->getIdentity()->credits = $row->credits;
        $this->user->getIdentity()->nick = $row->nick;
        $this->user->getIdentity()->email = $row->email;
    }

    /**
     * Gets real amount of credits player can use
     * @return int
     */
    public function getRealCredits()
    {
        $totalAmount = 0;
        foreach ($this->user->getIdentity()->bets as $bet)
        {
            $totalAmount += $bet['amount'];
        }

        return $this->user->getIdentity()->credits - $totalAmount;
    }

    /**
     *
     * @param int $amount
     * @throws Exception
     */
    public function validateBet($game, $amount, $data)
    {
        $replaced = 0;
        if ($amount > $this->getRealCredits())
        {
            throw new \Exception('Insuficient free credits. Please lower your bet at lest to ' . $this->getRealCredits() . ' Cr.');
        }

        foreach ($this->user->getIdentity()->bets as $bet => $param)
        {
            if ($param['game'] == $game && $param['data']['value'] == $data['value'])
            {
                unset($this->user->getIdentity()->bets[$bet]);
                $replaced++;
            }
        }

        return $replaced;
    }

    /**
     * Place a bet for current player
     * @param type $game
     * @param type $amount
     * @param type $value
     */
    public function addBet($game, $amount, $data)
    {
        $replaced = $this->validateBet($game, $amount, $data);

        $data = array(
            'game'   => $game,
            'amount' => $amount,
            'data'   => $data,
            'uid'    => $this->createBetUid()
        );

        $this->user->getIdentity()->bets[] = $data;
        return $replaced;
    }

    public function getBets($game)
    {
        $bets = array();

        foreach ($this->user->getIdentity()->bets as $bet)
        {
            if ($bet['game'] == $game)
            {
                $bets[] = $bet;
            }
        }
        return $bets;
    }

    /**
     * Create unique id
     * @return type
     */
    public function createBetUid()
    {
        return md5(time() * time() * rand());
    }

}
