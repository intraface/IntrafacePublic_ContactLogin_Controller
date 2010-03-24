<?php
class IntrafacePublic_ContactLogin_Controller_Debtor_Pdf extends k_Component
{
    protected $registry;

    function __construct(k_Registry $registry)
    {
        $this->registry = $registry;
    }

    function renderHtml()
    {
        return new k_SeeOther($this->url(null . '.pdf'));
    }

    function renderPdf()
    {
        $debtor_client = $this->registry->create('debtor', $this->context->context->getIntranet());
        $data = $debtor_client->getDebtorPdf($this->name());
        $data = $data->scalar;

        $response = new k_HttpResponse(200, $data);
        //response->setEncoding(NULL);
        $response->setContentType("application/pdf");
/*
        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment; filename=\"foobar.pdf\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");
*/
        return $response;
    }
    function map($name)
    {
        return 'IntrafacePublic_ContactLogin_Controller_Debtor_Payment';
    }

}