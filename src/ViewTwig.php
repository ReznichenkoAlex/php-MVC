<?php

namespace Base;

class ViewTwig extends AbstractView
{
    private $twig;

    public function render(string $tpl, $data = []): string
    {
        if (!$this->twig) {
            $loader = new \Twig\Loader\FilesystemLoader($this->templatePath);
            $this->twig = new \Twig\Environment($loader, ['autoescape' => false]);
        }

        return $this->twig->render($tpl, $data + $this->data);
    }

}
