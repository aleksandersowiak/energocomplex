<?php
abstract class BaseController extends BaseModel {
	protected $urlvalues;
	protected $action;
    protected $_params = array();
    public $_email = null;
public $_publicKey;
    public $_privateKey;
	public function __construct($action, $urlvalues) {
		$this->action = $action;
		$this->urlvalues = $urlvalues;
        $the_request = array();
        if (isset($_SERVER['REQUEST_METHOD'])) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $the_request = & $_GET;
                    break;
                case 'POST':
                    $the_request = & $_POST;
                    break;
                default:
            }
        }
        $this->setParams($the_request);
        $this->__init();
        $this->reCatchaInit();
        $conf = $this->getConfig();
        $this->_publicKey = $conf['recaptcha.public'];
        $this->_privateKey = $conf['recaptcha.private'];
	}
	public function __init() {
        if ($this->getParam('controller') == '' || $this->getParam('controller') == NULL || $this->getParam('controller') == false) {
            $this->setParam('controller','home');
        }
        if ($this->getParam('action') == '' || $this->getParam('action') == NULL || $this->getParam('action') == false) {
            $this->setParam('action','index');
        }
        if ($this->getParam('language') == "") {
            $this->setParam('language',DEFAULT_LANG);
        }

    }

    public function reCatchaInit() {
	    require ( APPLICATION_PATH . '/data/recaptcha/recaptchalib.php');
    }
	public function ExecuteAction() {
		return $this->{$this->action}();
	}
	
	protected function ReturnView($viewmodel, $fullview) {
        $viewloc = APPLICATION_PATH.'/views/' . get_class($this) . '/' . str_replace('Action','',$this->action) . '.phtml';
        if ($fullview) {
			require(APPLICATION_PATH.'/views/maintemplate.phtml');
		} else {
            require(APPLICATION_PATH.'/views/template/header.phtml'); // is set default header
            require($viewloc); // data from controller action
            require(APPLICATION_PATH.'/views/template/footer.phtml'); // is set default footer
		}
	}
    public function setParam($key, $value)
    {
        $key = (string)$key;
        if ((null === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (null !== $value) {
            $this->_params[$key] = $value;
        }
        return $this;
    }

    public function setParams(array $array)
    {
        $this->_params = $this->_params + (array)$array;
        foreach ($this->_params as $key => $value) {
            if (null === $value) {
                unset($this->_params[$key]);
            }
        }
        return $this;
    }

    public function getParam($key, $default = null)
    {
        $key = (string)$key;
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
        return $default;
    }

    public function getParams()
    {
        return $this->_params;
    }
    public function mailClass()
    {
        include(dirname(__FILE__) . "/mailer/class.phpmailer.php");
        $email = new PHPMailer(true);
        $this->_email = $email;
        include(dirname(__FILE__) . "/mailer/setparameters.php");
        return $this->_email;
    }
}
