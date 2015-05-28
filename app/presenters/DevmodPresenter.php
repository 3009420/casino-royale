<?php

namespace App\Presenters;

use App;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DevmodPresenter
 *
 * @author dantem
 */
class DevmodPresenter extends BasePresenter
{

    /** @var App\Model\Devmod @inject */
    public $devmod;

    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole('admin'))
        {
            $this->redirect(403);
        }
    }

    public function actionAddCash($amount)
    {
        $this->devmod->addCredits($amount);
        $this->flashMessage('Added ' . $amount . ' Cr to your account.');
        $this->redirect('Devmod:');
    }

}
