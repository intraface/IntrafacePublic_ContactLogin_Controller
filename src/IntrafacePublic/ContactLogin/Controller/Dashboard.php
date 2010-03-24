<?php
class IntrafacePublic_ContactLogin_Controller_Dashboard extends k_Component
{
    protected $registry;
    protected $template;

    function __construct(k_Registry $registry, k_TemplateFactory $template)
    {
        $this->registry = $registry;
        $this->template = $template;
    }

    protected function map($name)
    {
        if ($name == "profile") {
            return 'IntrafacePublic_ContactLogin_Controller_Contact';
        } elseif ($name == "debtor") {
            return 'IntrafacePublic_ContactLogin_Controller_Debtor_Index';
        } elseif ($name == "newsletter") {
            return 'IntrafacePublic_ContactLogin_Controller_Newsletter_Index';
        }
    }

    function dispatch()
    {
        if ($this->identity()->anonymous()) {
            throw new k_NotAuthorized();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $tpl = $this->template->create(dirname(__FILE__) . '/../templates/dashboard');
        return $tpl->render($this);
    }

    function getIntranet()
    {
        return $this->context->getIntranet();
    }

    function execute()
    {
        return $this->wrap(parent::execute());
    }

    function wrapHtml($content)
    {
        $contact = $this->registry->create('contact', $this->context->getIntranet());
        $intranet_permissions = $contact->getIntranetPermissions();
        $navigation = array();

        $value = $contact->getContact($this->identity()->user());

        $navigation[] = 'profile';

        if (in_array('newsletter', $intranet_permissions)):
            $navigation[] = 'newsletter';
        endif;
        if (in_array('debtor', $intranet_permissions)):
            $navigation[] = 'debtor';
        endif;

        $tpl = $this->template->create(dirname(__FILE__) . '/../templates/navigation');
        $nav = $tpl->render($this, array('navigation' => $navigation, 'email' => $value['email']));

        return $nav . $content;
    }
}
