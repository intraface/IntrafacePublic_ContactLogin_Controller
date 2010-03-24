<?php
require_once 'recursive_array_map.php';

class IntrafacePublic_ContactLogin_Controller_Newsletter_Index extends k_Component
{
    protected $registry;

    function __construct(k_Registry $registry)
    {
        $this->registry = $registry;
    }

    function renderHtml()
    {
        $contact = $this->registry->create('contact', $this->context->getIntranet());;
        $newsletter = $this->registry->create('newsletter', $this->context->getIntranet());

        $newsletterlists = $newsletter->getNewsletterList();

        //$newsletterlists = recursive_array_map('utf8_decode', $newsletterlists);

        $contact_array = $contact->getContact($this->identity()->user());
        //$contact_array = recursive_array_map('utf8_decode', $contact_array);

        /*
        if (!empty($this->GET['optin'])) {
            $newsletter->optin($this->GET['list_id'], $this->GET['code'], $_SERVER['REMOTE_ADDR']);
            throw new k_http_Redirect($this->url());
        }
        */

        $subscriptions = $newsletter->getSubscriptions($this->identity()->user());
        $optin = $newsletter->needOptin($this->identity()->user());

        $this->document->setTitle($this->t('Newsletters'));

        if (!empty($contact_array['email'])) {
            $data = array('caption' => $this->t('Newsletter lists'),
                          'msg' => $text,
                          'newsletterlists' => $newsletterlists,
                          'subscriptions' => $subscriptions,
                          'optin' => $optin);
        } else {
            return '<p class="error">Du har ikke registreret en e-mail adresse, og derfor kan du ikke abonnere p� nyhedsbreve.</p>';
        }
        $smarty = new k_Template(dirname(__FILE__) . '/../../templates/newsletterlist.tpl.php');
        return $smarty->render($this, $data);

    }

    function postForm()
    {
        $contact = $this->registry->create('contact', $this->context->getIntranet());
        $newsletter = $this->registry->create('newsletter', $this->context->getIntranet());

        $newsletterlists = $newsletter->getNewsletterList();

        $contact_array = $contact->getContact($this->identity()->user());
        $contact_array = recursive_array_map('utf8_decode', $contact_array);

        if ($this->body('subscribe')) {
            foreach ($this->body('subscribe') as $list_id => $value) {
                $newsletter->setListId(intval($list_id));
                $newsletter->subscribe($contact_array['email'], $_SERVER['REMOTE_ADDR']);
            }
        }

        if ($this->body('unsubscribe')) {
            foreach ($this->body('unsubscribe') as $list_id => $value) {
                $newsletter->setListId(intval($list_id));
                $newsletter->unsubscribe($contact_array['email']);
            }
        }

        if ($this->body('confirm')) {
            $code = $this->body('code');
            foreach ($this->body('confirm') as $list_id => $value) {
                $newsletter->setListId(intval($list_id));
                $newsletter->optIn($code[$list_id], $_SERVER['REMOTE_ADDR']);
            }
        }

        return new k_SeeOther($this->url());
    }
}