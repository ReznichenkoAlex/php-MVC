<?php

namespace Base;

interface ViewInterface
{
    public function render(string $tpl, $data = []);
}