<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
abstract class GamePresenter extends BasePresenter
{

    /**
     * Game version
     * @var type string
     */
    public $version = 'indev';

}
