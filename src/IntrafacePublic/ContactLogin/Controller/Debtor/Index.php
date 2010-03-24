<?php
class IntrafacePublic_ContactLogin_Controller_Debtor_Index extends k_Component
{
    protected $registry;

    function __construct(k_Registry $registry)
    {
        $this->registry = $registry;
    }

    function getContactId()
    {
        return $this->identity()->user();
    }

    function renderHtml()
    {
        $debtor_client = $this->registry->create('debtor', $this->context->getIntranet());

        //$quotations = $debtor_client->getDebtorList('quotation', $this->getContactId());
        //$orders = $debtor_client->getDebtorList('order', $this->getContactId());
        $invoices = $debtor_client->getDebtorList('invoice', $this->getContactId());

        $this->document->setTitle('Debtor list');

        $string = '';

        if (!empty($quotations) AND count($quotations) > 0) {
            $data = array('caption' => $this->t('Quotations'),
                          'debtors' => $quotations);
        $smarty = new k_Template(dirname(__FILE__) . '/../../templates/debtors.tpl.php');
        $string .= $smarty->render($this, $data);
        }

        if (!empty($orders) AND count($orders) > 0) {
            $data = array('caption' => $this->t('Orders'),
                          'debtors' => $orders);

        $smarty = new k_Template(dirname(__FILE__) . '/../../templates/debtors.tpl.php');
        $string .= $smarty->render($this, $data);
                    }

        if (!empty($invoices) AND count($invoices) > 0) {
            $data = array('caption' => $this->t('Invoices'),
                          'debtors' => $invoices);
        $smarty = new k_Template(dirname(__FILE__) . '/../../templates/debtors.tpl.php');
        $string .= $smarty->render($this, $data);
                    }

        if (empty($invoices) AND empty($quotations) AND empty($orders)) {
            $string  = '<p>'.$this->t('There is no debtor items at the moment.').'</p>';
        }

        return '<h1>'.$this->t('Debtor list').'</h1><p><a href="'.$this->url('../').'">'.$this->t('Go back').'</a></p>' . $string;
    }

    function map($name)
    {
        return 'IntrafacePublic_ContactLogin_Controller_Debtor_Pdf';
    }
}