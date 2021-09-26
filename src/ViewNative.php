<?php

namespace Base;

class ViewNative extends AbstractView
{

    public function render(string $tpl, $data = []): string
    {
        $this->data += $data;
        ob_start();
        include $this->templatePath . DIRECTORY_SEPARATOR . $tpl;
        return ob_get_clean();
    }


}
