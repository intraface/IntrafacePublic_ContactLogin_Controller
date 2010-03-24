<?php
/**
 * Logout page
 *
 * @package ContactLogin
 * @author  Lars Olesen <lars@legestue.net>
 * @since   0.1.0
 * @version @package-version@
 */

class IntrafacePublic_ContactLogin_Controller_Logout extends k_Component
{
    protected $template;
    protected $registry;

    function __construct(k_TemplateFactory $template, k_Registry $registry)
    {
        $this->template = $template;
        $this->registry = $registry;
    }

    function execute()
    {
        $this->url_state->init("continue", $this->url('../'));
        return parent::execute();
    }

    function postForm()
    {
        $this->session()->set('identity', null);
        return new k_SeeOther($this->query('continue'));
    }
}