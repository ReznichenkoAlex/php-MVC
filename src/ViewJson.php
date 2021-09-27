<?php

namespace Base;

class ViewJson extends AbstractView
{
    public function render($tpl, $data = [])
    {
        if ($data) {
            return $data;
        }
        header("Content-Type: application/json");
        return json_encode($tpl);
    }

}