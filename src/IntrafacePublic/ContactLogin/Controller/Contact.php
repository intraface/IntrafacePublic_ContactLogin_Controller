<?php
class IntrafacePublic_ContactLogin_Controller_Contact extends k_Component
{
    protected $registry;
    protected $form;
    protected $template;

    function __construct(k_Registry $registry, k_TemplateFactory $template)
    {
        $this->registry = $registry;
        $this->template = $template;
    }

    function renderHtml()
    {
        $contact = $this->registry->create('contact', $this->context->context->getIntranet());
        $contact_array = $contact->getContact($this->identity()->user());
        $data = array('contact' => $contact_array);

        $smarty = $this->template->create(dirname(__FILE__) . '/../templates/contact');
        return $smarty->render($this, $data);
    }

    function renderHtmlEdit()
    {
        $this->document->setTitle($this->t('Edit information'));

        $contact = $this->registry->create('contact', $this->context->context->getIntranet());
        $values = $contact->getContact($this->identity()->user());

        $this->getForm()->setDefaults(array(
            'id' => $values['id'],
            'name' => $values['name'],
            'address' => $values['address'],
            'postcode' => $values['postcode'],
            'city' => $values['city'],
            'phone' => $values['phone'],
            'email' => $values['email']
        ));

        $data = array('form' => $this->getForm()->toHTML(), 'msg' => '');
        $smarty = $this->template->create(dirname(__FILE__) . '/../templates/edit');
        return $smarty->render($this, $data);
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $contact = $this->registry->create('contact', $this->context->context->getIntranet());

            $values = $this->body();

            if ($contact->saveContact($values)) {
                return new k_SeeOther($this->url(null, array('flare' => 'Contact updated')));
            } else {
                $msg = $this->t('Could not save.');
            }
        }
        return $this->render();
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('edit', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('text', 'name', $this->t('Name'), array('size' => 40));
        $form->addElement('text', 'address', $this->t('Address'),  array('size' => 40));
        $form->addElement('text', 'postcode', $this->t('Zip code'),  array('size' => 4));
        $form->addElement('text', 'city', $this->t('City'),  array('size' => 40));
        $form->addElement('text', 'email', $this->t('Email'),  array('size' => 40));
        $form->addElement('text', 'phone', $this->t('Phone'),  array('size' => 8));
        $form->addElement('submit', NULL, $this->t('Save'));

        return ($this->form = $form);
    }
}