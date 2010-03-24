<?php
class IntrafacePublic_ContactLogin_Root extends k_Component
{
    protected function map($name)
    {
        return 'IntrafacePublic_ContactLogin_Controller_Index';
    }

    public function renderHtml()
    {
        return '<h1>Intentionally left blank</h1><p>You cannot use kundelogin.dk without an intranet identifier provided by intraface.dk.</p>';
    }

    function getIntranet()
    {
        return $this->name();
    }
}