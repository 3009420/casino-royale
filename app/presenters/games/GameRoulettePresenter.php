<?php

namespace App\Presenters;

use Nette;

/**
 * Homepage presenter.
 */
class GameRoulettePresenter extends GamePresenter
{

    /** @var \App\Forms\Games\Roulette\BetFormFactory @inject */
    public $betFormFactory;

    /** @var \App\Model\Player @inject */
    public $player;

    /** @var \App\Model\Games\Roulette @inject */
    public $gamemodel;

    public function startup()
    {
        parent::startup();
        $this->player->getRealCredits();

        $this->version = $this->gamemodel->getVersion();
    }

    /**
     * Bet form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentBetForm()
    {
        $form = $this->betFormFactory->create();
        $form->onSuccess[] = callback($this, 'betFormSucceeded');
        return $form;
    }

    public function betFormSucceeded($form, $values)
    {

        try
        {
            $this->gamemodel->placeBet($values['amount'], $values['value']);
        }
        catch (\Exception $e)
        {
            $this->flashMessage($e->getMessage());
        }
    }

}
