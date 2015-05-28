<?php

namespace App\Forms\Games\Roulette;

use Nette;
use Nette\Application\UI\Form;

class BetFormFactory extends Nette\Object
{

    /**
     * @return Form
     */
    public function create()
    {
        $form = new Form;
        $form->addText('amount', 'Amount')
          ->setRequired('Please specify bet amount.')
          ->addRule(Form::MIN, 'Amount cannot be less than 1 Cr', 0);

        $form->addHidden('value');
        $form->addSubmit('bet', 'Place a bet');

        return $form;
    }

}
