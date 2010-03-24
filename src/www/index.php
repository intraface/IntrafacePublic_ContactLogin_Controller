<?php
require_once 'config.local.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';
require_once 'Ilib/ClassLoader.php';
require_once 'k.php';
require_once 'swift_required.php';

class k_SessionIdentityLoader implements k_IdentityLoader {
  function load(k_Context $context) {
    if ($context->session('identity')) {
      return $context->session('identity');
    }
    return new k_Anonymous();
  }
}

class NotAuthorizedComponent extends k_Component {
  function dispatch() {
    // redirect to login-page
    return new k_TemporaryRedirect($this->url('../login', array('continue' => $this->requestUri())));
  }
}
class Intraface_TemplateFactory extends k_DefaultTemplateFactory
{
    function __construct() {}

    function create($filename)
    {
        $filename = $filename . '.tpl.php';
        $__template_filename__ = k_search_include_path($filename);
        if (!is_file($__template_filename__)) {
            throw new Exception("Failed opening '".$filename."' for inclusion. (include_path=".ini_get('include_path').")");
        }
        return new k_Template($__template_filename__);
    }
}

class ContactLogin_Factory
{
    function new_k_TemplateFactory()
    {
        return new Intraface_TemplateFactory;
    }

    function new_Translation2()
    {
        $options = array(
        	"da" => true,
            "en" => true
        );

        $language = HTTP::negotiateLanguage($options, "en");

        $translation = IntrafacePublic_Frontend_Translation::factory($language);
        $translation->setPageID("kundelogin");
        return $translation;
    }

    function new_k_Registry()
    {
    	$registry = new k_Registry();

    	$registry->registerConstructor('admin', create_function(
          '$className, $args, $registry',
          'return new IntrafacePublic_Admin_Client_XMLRPC(INTRAFACE_MASTER_PASSWORD, false, PATH_XMLRPC . "admin/server.php");'
        ));

        $registry->registerConstructor('contact', create_function(
          '$className, $args, $registry',
          '$credentials["private_key"] = $registry->get("admin")->getPrivateKey($args[0]);
           $credentials["session_id"] = md5(session_id());
           $contact = new IntrafacePublic_Contact_Client_XMLRPC($credentials, false, PATH_XMLRPC . "contact/server.php");
           return $contact;
          '
        ));

        $registry->registerConstructor('newsletter', create_function(
          '$className, $args, $registry',
          '$credentials["private_key"] = $registry->get("admin")->getPrivateKey($args[0]);
           $credentials["session_id"] = md5(session_id());
           $newsletter = new IntrafacePublic_Newsletter_Client_XMLRPC($credentials, null, false, PATH_XMLRPC . "newsletter/server.php");
           return $newsletter;
          '
        ));

        $registry->registerConstructor('debtor', create_function(
          '$className, $args, $registry',
          '$credentials["private_key"] = $registry->get("admin")->getPrivateKey($args[0]);
           $credentials["session_id"] = md5(session_id());
           $debtor = new IntrafacePublic_Debtor_XMLRPC_Client($credentials, false, PATH_XMLRPC . "debtor/server.php");
           return $debtor;
          '
        ));
        return $registry;
    }


    function new_Swift_Message($c)
    {
        return Swift_Message::newInstance();
    }

    function new_Swift_Mailer($c)
    {
        return Swift_Mailer::newInstance($this->new_Swift_Transport($c));
    }

    function new_Swift_Transport()
    {
        return Swift_MailTransport::newInstance();
    }

}

function create_container() {
    return new bucket_Container(new ContactLogin_Factory());
}

class EnglishLanguage implements k_Language {
  function name() {
    return 'English';
  }
  function isoCode() {
    return 'en';
  }
}

class SwedishLanguage implements k_Language {
  function name() {
    return 'Swedish';
  }
  function isoCode() {
    return 'sv';
  }
}

class MyLanguageLoader implements k_LanguageLoader {
  function load(k_Context $context) {
    if($context->query('lang') == 'sv') {
      return new SwedishLanguage();
    } else if($context->query('lang') == 'en') {
      return new EnglishLanguage();
    }
    return new EnglishLanguage();
  }
}

class SimpleTranslator implements k_Translator {
  protected $phrases;
  function __construct($phrases = array()) {
    $this->phrases = $phrases;
  }
  function translate($phrase, k_Language $language = null) {
    return isset($this->phrases[$phrase]) ? $this->phrases[$phrase] : $phrase;
  }
}

class SimpleTranslatorLoader implements k_TranslatorLoader {
  function load(k_Context $context) {
    // Default to English
    $phrases = array(
      'Hello' => 'Hello',
      'Meatballs' => 'Meatballs',
    );
    if($context->language()->isoCode() == 'sv') {
      $phrases = array(
        'Hello' => 'Bork, bork, bork!',
        'Meatballs' => 'Swedish meatballs',
      );
    }
    return new SimpleTranslator($phrases);
  }
}
/*
  $factory = new ApplicationFactory();
  $container = new bucket_Container($factory);
  $factory->template_dir = realpath(dirname(__FILE__) . '/../templates');
  $factory->pdo_dsn = 'mysql:host=localhost;dbname=test';
  $factory->pdo_username = 'root';
  return $container;
*/
if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {
  $components = new k_InjectorAdapter(create_container());
  //$components = new k_DefaultComponentCreator();
  $components->setImplementation('k_DefaultNotAuthorizedComponent', 'NotAuthorizedComponent');
  $identity_loader = new k_SessionIdentityLoader();
  k()
    ->setComponentCreator($components)
    ->setLanguageLoader(new MyLanguageLoader())->setTranslatorLoader(new SimpleTranslatorLoader())
    ->setIdentityLoader($identity_loader)
    ->run('IntrafacePublic_ContactLogin_Root')
    ->out();
}
