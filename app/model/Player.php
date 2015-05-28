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
    public function validateBet($amount)
    {
        if ($amount > $this->getRealCredits())
        {
            throw new \Exception('Insuficient free credits. Please lower your bet at lest to ' . $this->getRealCredits() . ' Cr.');
        }
    }

    /**
     * Place a bet for current player
     * @param type $game
     * @param type $amount
     * @param type $value
     */
    public function addBet($game, $amount, $value)
    {
        $this->validateBet($amount);

        $data = array(
            'game'   => $game,
            'amount' => $amount,
            'value'  => $value,
            'uid'    => $this->createBetUid()
        );

        $this->user->getIdentity()->bets[] = $data;
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
