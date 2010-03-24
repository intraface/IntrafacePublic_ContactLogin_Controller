<?php
/**
 * Retrieve password
 *
 * @package Intraface
 * @author  Lars Olesen <lars@legestue.net>
 * @since   0.1.0
 * @version @package-version@
 */
class IntrafacePublic_ContactLogin_Controller_RetrievePassword extends k_Component
{
    public $msg;
    protected $template;
    protected $email;
    protected $mailer;

    function __construct(k_Registry $registry, k_TemplateFactory $template, Swift_Message $email, Swift_Mailer $mailer)
    {
        $this->template = $template;
        $this->email = $email;
        $this->mailer = $mailer;
        $this->registry = $registry;
    }

    function execute()
    {
        $this->url_state->init("continue", $this->url('../login', array('flare' => 'Vi har sendt en e-mail til dig med yderligere information')));
        return parent::execute();
    }

    function renderHtml()
    {
        $this->document->setTitle('Retrieve forgotten password');

        $smarty = $this->template->create(dirname(__FILE__) . '/../templates/retrievepassword');
        return $smarty->render($this);
    }

    function postForm()
    {
        $contact = $this->registry->create('contact', $this->getIntranet());
        $contacts = $contact->findByEmail($this->body('email'));

        $count = count($contacts);

        if ($count == 0) {
            return new k_SeeOther($this->url(null, array('flare' => 'No users found on that e-mail')));
        }

        $body  = "Det var dejligt at snakke med et levende menneske igen, og jeg er bare glad for at jeg kan hjælpe.\n\n";
        if ($count > 1) {
            $body  .= "Vi fandt flere end en kontakt på denne e-mail-adresse, så det betyder, at du får tilsendt flere koder til at logge ind med. Hvis du vil have slået adresserne sammen, kan du kontakte shoppens ejere.\n\n";
        }

        $table = new Console_Table;
        $table->setHeaders(array('#', 'Navn', 'Kode'));

        foreach ($contacts as $c) {
            $contact_row = array($c['number'], $c['name'], $c['code']);
            $table->addRow($contact_row);
        }

        $body .= utf8_encode($table->getTable()) . "\n\n";

        $body .= "Med venlig hilsen\nDin hengivne webserver";

        $this->email
            ->setSubject('Din adgangskode')
            ->setFrom(array('robot@intraface.dk' => 'Intraface.dk'))
            ->setTo(array($this->body('email')))
            ->setBody($body);

    	if (!$this->mailer->send($this->email)) {
    	    $this->msg = '<p>Det gik <strong>ikke</strong> godt. E-mailen kunne ikke sendes. Du kan prøve igen senere.</p>';
    	    return $this->render();
    	}
    	return new k_SeeOther($this->query('continue'));
    }

    function getIntranet()
    {
        return $this->context->getIntranet();
    }
}