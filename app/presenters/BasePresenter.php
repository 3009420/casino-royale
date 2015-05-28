<?php

namespace App\Presenters;

use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var \App\Model\Player @inject */
    public $player;

    public function startup()
    {
        parent::startup();

        $user = $this->getUser();

        if (!$user->loggedIn && $this->presenter->name != 'Sign')
        {
            $this->redirect('Sign:in');
        }
    }

    public function getPlayerRealCredits()
    {
        return $this->player->getRealCredits();
    }

    public function createComponentCss()
    {
        $files = new \WebLoader\FileCollection();
        $files->addFiles($this->context->parameters['webloader']['css']['default']['files']);
        $files->addFiles(Nette\Utils\Finder::findFiles('*.css')->from($this->context->parameters['wwwDir'] . '/css'));

        $files->removeFiles($this->context->parameters['webloader']['css']['print']);

        $compiler = \WebLoader\Compiler::createCssCompiler($files, $this->context->parameters['wwwDir'] . '/webtemp');
        return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
    }

    public function createComponentJs()
    {
        $files = new \WebLoader\FileCollection();
        $files->addFiles($this->context->parameters['webloader']['js']['default']['files']);
        $files->addFiles(Nette\Utils\Finder::findFiles('*.js')->from($this->context->parameters['wwwDir'] . '/js'));

        $compiler = \WebLoader\Compiler::createJsCompiler($files, $this->context->parameters['wwwDir'] . '/webtemp');
        return new \WebLoader\Nette\JavaScriptLoader($compiler, $this->template->basePath . '/webtemp');
    }

}
