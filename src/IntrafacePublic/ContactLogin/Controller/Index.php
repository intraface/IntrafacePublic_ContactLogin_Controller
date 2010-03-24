<?php
class IntrafacePublic_ContactLogin_Controller_Index extends k_Component
{
    private $template;
    private $registry;

    function __construct(k_TemplateFactory $template, k_Registry $registry)
    {
        $this->template = $template;
        $this->registry = $registry;
    }

    protected function map($name)
    {
        switch ($name) {
            case 'dashboard':
                return 'IntrafacePublic_ContactLogin_Controller_Dashboard';
            case 'login':
                return 'IntrafacePublic_ContactLogin_Controller_Login';
            case 'logout':
                return 'IntrafacePublic_ContactLogin_Controller_Logout';
            case 'retrievepassword':
                return 'IntrafacePublic_ContactLogin_Controller_RetrievePassword';
        }
    }

    function wrapHtml($content)
    {
        $data = array('content' => $content);
        $smarty = $this->template->create(dirname(__FILE__) . '/../templates/main');
        return $smarty->render($this, $data);
    }

    function execute()
    {
        return $this->wrap(parent::execute());
    }

    function getIntranet()
    {
        return $this->name();
    }

    /*
    private function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('login', 'get', $this->url('login'));
        $form->addElement('text', 'code', $this->t('code'));
        $form->addElement('submit', null, $this->t('login'));

        $form->addRule('code', $this->t('enter code'), 'required');

        $form->applyFilter('__ALL__', 'trim');
        $form->applyFilter('__ALL__', 'strip_tags');
        $form->applyFilter('__ALL__', 'addslashes');

        return ($this->form = $form);
    }
    */

    function GET()
    {
        if ($this->query('optin') != '') {
            $newsletter = $this->registry->create('newsletter', $this->getIntranet());
            $list_id = $newsletter->getListIdFromOptinCode($this->query('optin'));

            if ($list_id) {
                if ($newsletter->OptIn($list_id, $this->query('optin'), $_SERVER['REMOTE_ADDR'])) {
                    return new k_SeeOther($this->url(null, array('flare' => 'You have been subscribed')));
                } else {
                    return new k_SeeOther($this->url(null, array('flare' => 'You have not been subscribed')));
                }
            }
        }
        return parent::GET();
    }

    function renderHtml()
    {
        return new k_SeeOther($this->url('login'));
        //return '<p>You are ready to log into the "' . $this->name() . '" contactlogin.</p>' . $this->getForm()->toHtml();
    }
}