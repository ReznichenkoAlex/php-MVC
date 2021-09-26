<?php

namespace Base;

abstract class AbstractView implements ViewInterface
{
    protected $templatePath = '';
    protected $data = [];

    public function __construct()
    {
        $this->templatePath = TEMPLATE_PATH;
    }

    public function assign(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($varName)
    {
        return $this->data[$varName] ?? null;
    }
}