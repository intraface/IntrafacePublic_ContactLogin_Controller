<?php
class IntrafacePublic_ContactLogin_Controller_Debtor_Payment extends k_Component
{
    protected $registry;

    function __construct(k_Registry $registry)
    {
        $this->registry = $registry;
    }

    function renderHtml()
    {
        $debtor_client = $this->registry->create('debtor', $this->context->context->context->getIntranet());
        $debtor_id = $this->context->name();

        $this->document->setTitle('Payment');

        $data = array();
        $smarty = new k_Template(dirname(__FILE__) . '/../../templates/payment.tpl.php');
        return $smarty->render($this, $data);

    }
}