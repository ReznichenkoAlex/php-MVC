<?php

namespace Base;

use App\Model\User;

abstract class AbstractController
{
    /** @var AbstractView */
    protected $view;
    /** @var array  */
    protected $user;

    protected function redirect(string $url)
    {
        throw new RedirectException($url);
    }


    public function setView(AbstractView $view): void
    {
        $this->view = $view;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }


}