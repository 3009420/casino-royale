<?php

namespace App\Presenters;

use Nette,
    App\Forms\SignFormFactory;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{

    /** @var SignFormFactory @inject */
    public $factory;

    public function startup()
    {
        parent::startup();
        $this->template->disableNavbar = true;
        $this->template->narrowLayout = true;
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        $form = $this->factory->create();
        $form->onSuccess[] = function ($form) {
            $form->getPresenter()->redirect('Dashboard:');
        };
        return $form;
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.');
        $this->redirect('in');
    }

}
