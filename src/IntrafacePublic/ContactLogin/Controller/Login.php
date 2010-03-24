<?php
class IntrafacePublic_ContactLogin_Controller_Login extends k_Component
{
    private $form;
    private $template;
    private $registry;

    function __construct(k_TemplateFactory $template, k_Registry $registry)
    {
        $this->template = $template;
        $this->registry = $registry;
    }

    private function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('login', 'get', $this->url());
        $form->addElement('text', 'code', $this->t('code'));
        $form->addElement('submit', null, $this->t('login'));

        $form->addRule('code', $this->t('enter code'), 'required');

        $form->applyFilter('__ALL__', 'trim');
        $form->applyFilter('__ALL__', 'strip_tags');
        $form->applyFilter('__ALL__', 'addslashes');

        return ($this->form = $form);
    }

    function execute()
    {
        $this->url_state->init("continue", $this->url('../dashboard'));
        return parent::execute();
    }

    function renderHtml()
    {
        $this->document->setTitle('Login');

        $msg = '';

        if ($this->getForm()->validate()) {
            $contact = $this->getContact();
            if ($contact_array = $contact->authenticateByKey($this->query('code'))) {
                //$user = $this->selectUser($this->body('username'), $this->body('password'));
                //if ($user) {

                $user = new k_AuthenticatedUser($contact_array['id']);

                $this->session()->set('identity', $user);

                if ($this->query('optin') != '') {
                    $newsletter = $this->registry->create('newsletter', $this->context->getIntranet());
                    $list_id = $newsletter->getListIdFromOptinCode($this->query('optin'));

                    if ($list_id) {
                        try {
                            if ($newsletter->OptIn($list_id, $this->query('optin'), $_SERVER['REMOTE_ADDR'])) {
                                return new k_SeeOther($this->url('../dashboard/newsletter', array('flare' => 'You have been subscribed to the newsletter.')));
                            }
                        } catch (Exception $e) {
                            // @todo handle this situation bettter
                            die($e->getMessage());
                        }

                    }
                }

                return new k_SeeOther($this->query('continue'));

            }
            $msg = 'Could not log in with ose credentials.';
        }

        $data = array('form' => $this->getForm()->toHTML(),
                      'msg' => $msg,
                      'name' => $this->context->name());

        $smarty = $this->template->create(dirname(__FILE__) . '/../templates/login');
        return $smarty->render($this, $data);
    }

    function getContact()
    {
        return $this->registry->create('contact', $this->context->getIntranet());
    }
}