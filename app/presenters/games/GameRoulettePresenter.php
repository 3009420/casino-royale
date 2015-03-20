<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class GameRoulettePresenter extends GamePresenter
{

    public function startup()
    {
        parent::startup();

        $this->version = '0.0.1';
    }

}
