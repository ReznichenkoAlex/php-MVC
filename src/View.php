<?php

namespace Base;

class View
{
    private $templatePath = '';
    private $data = [];

    public function __construct()
    {
        $this->templatePath = TEMPLATE_PATH;
    }

    public function assign(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function render(string $tpl, $data = []):string
    {
        $this->data += $data;
        ob_start();
        include $this->templatePath . DIRECTORY_SEPARATOR . $tpl;
        return ob_get_clean();
    }

    public function __get($varName)
    {
        return $this->data[$varName] ?? null;
    }
}
