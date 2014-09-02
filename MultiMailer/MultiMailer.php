<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.0
 * 
 * MultiMailer is the Yii extension created to send or store emails in database
 * with the help of the amazing PHPMailer class.
 * @see https://github.com/bizley-code/Yii-MultiMailer
 * 
 * See Examples folder for configuration and usage examples.
 * 
 * MultiMailer requires Yii version 1.1.
 * @see http://www.yiiframework.com
 * @see https://github.com/yiisoft/yii
 * 
 * MultiMailer 1.0 uses PHPMailer version 5.2.8.
 * @see http://phpmailer.worxware.com
 * @see https://github.com/Synchro/PHPMailer
 * 
 * @todo CC and BCC headers, sendmail, qmail
 */

require_once \dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'PHPMailerAutoload.php';

class MultiMailer extends CApplicationComponent
{
    /**
     * Default log category name.
     */
    const LOG_CATEGORY = 'ext.MultiMailer';
    
    /**
     * Default DB model column name of recipient's email address.
     */
    const COLUMN_EMAIL = 'email';
    
    /**
     * Default DB model column name of recipient's name.
     */
    const COLUMN_NAME = 'name';
    
    /**
     * Default DB model column name of mail subject.
     */
    const COLUMN_SUBJECT = 'subject';
    
    /**
     * Default DB model column name of mail body (html text body).
     */
    const COLUMN_BODY = 'body';
    
    /**
     * Default DB model column name of mail alternative body (plain text body).
     */
    const COLUMN_ALTBODY = 'alt';
    
    /**
     * Array key to store additional model properties.
     */
    const KEY_OTHERS = 'others';
    
    /**
     * Error messages
     */
    const ERR_DB_MODEL_INIT             = 'Error while initialising Db model.';
    const ERR_DB_MODEL_NOT_SET          = 'Database AR email model not set.';
    const ERR_DB_MODEL_SAVE             = 'Error while saving Db model.';
    const ERR_DB_PROPERTY_TYPE          = 'Database AR email model property must be of string or null type.';
    const ERR_EMAIL_INVALID             = 'Invalid email address.';
    const ERR_PHPMAILER_NOT_SET         = 'PHPMailer need to be set first.';
    const ERR_YII_CONTROLLER_NOT_SET    = 'Yii controller is not set.';
    
    /**
     * PROTECTED PROPERTIES ====================================================
     * Don't need to be changed.
     */
    
    /**
     * @var PHPMailer object, default null.
     */
    protected $_phpmailer = null;
    
    /**
     * @var DB AR model object, default null.
     */
    protected $_model = null;
    
    /**
     * @var boolean initialisation state, default false.
     */
    protected $_initState = false;
    
    /**
     * @var string error message, default null.
     */
    protected $_error = null;
    
    /**
     * @var string name of the view to be rendered, default null.
     */
    protected $_template = null;
    
    /**
     * @var array list of recipients' email addresses, default array().
     */
    protected $_addresses = array();
    
    /**
     * @var string|array body of mail, default null.
     */
    protected $_body = null;

    /**
     * @var string baseline directory for images path, default ''.
     * @see PHPMailer::msgHTML()
     */
    protected $_bodyBasedir = '';
    
    /**
     * @var boolean advanced HTML to text converter flag default false.
     * @see PHPMailer::msgHTML()
     */
    protected $_bodyAdvanced = false;
    
    /**
     * @var array default DB model columns list.
     */
    protected $_defaultColumns    = array(
        self::COLUMN_EMAIL      => self::COLUMN_EMAIL,
        self::COLUMN_NAME       => self::COLUMN_NAME,
        self::COLUMN_SUBJECT    => self::COLUMN_SUBJECT,
        self::COLUMN_BODY       => self::COLUMN_BODY,
        self::COLUMN_ALTBODY    => self::COLUMN_ALTBODY,
    );
    
    /**
     * PUBLIC PROPERTIES =======================================================
     * Changed from the Yii main config.
     */
    
    /**
     * @var array options for PHPMailer, default array().
     * You can set any available options for PHPMailer here i.e.
     * array(
     *  'Host' => 'mail.example.com',
     *  'Port' => 25,
     *  'SMTPAuth' => true,
     * )
     * Some PHPMailer options are set by default so you don't need to set everything.
     * @see setDefaultPHPMailerOptions()
     * @see PHPMailer documentation for details
     */
    public $setOptions = array();
    
    /**
     * @var string method for email sending, default 'MAIL'.
     * Options:
     * 'MAIL'   use PHP's mail function [http://php.net/manual/en/function.mail.php]
     * 'SMTP'   use SMTP server [http://en.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol]
     * 'DB'     store email in database instead of sending it immediately
     */
    public $setMethod = 'MAIL';
    
    /**
     * @var string email content body type, default 'html'.
     * Options:
     * 'html' use HTML tags to format email's body
     * anything else sets body to plain text
     */
    public $setContentType = 'html';
    
    /**
     * @var string sender's email address, default ''.
     */
    public $setFromAddress = '';
    
    /**
     * @var string sender's name, default ''.
     */
    public $setFromName = '';
    
    /**
     * @var string reply email address, default ''.
     */
    public $setReplyAddress = '';
    
    /**
     * @var string reply name, default ''.
     */
    public $setReplyName = '';
    
    /**
     * @var boolean flag to set reply address and name same as sender's, default true.
     */
    public $setSameReply = true;
    
    /**
     * @var boolean flag to switch logging on, default true.
     * @see Yii::log()
     */
    public $setLogging = true;
    
    /**
     * @var string DB AR model's name, default ''.
     * To use with 'DB' method. This is the name of the AR model for database 
     * table used to store email to be sent later for example by Java worker.
     */
    public $setDbModel = '';
    
    /**
     * array DB AR model's properties, default array()
     * Default properties are:
     * array(
     *  'email'     => 'email',
     *  'name'      => 'name',
     *  'subject'   => 'subject',
     *  'body'      => 'body',
     *  'alt'       => 'alt',
     * )
     * set as constants COLUMN_* as seen at the beginning of the class.
     * These will be added automatically so set anything here only if you want to 
     * change the column's name. For example in your DB there is column 'address'
     * instead of 'email' to store sender's email address so add here
     * array('email' => 'address').
     * If you want to skip any column just set its value to null.
     * If you want to set any additional properties for the model do it like this:
     * array(
     *  'others' => array(
     *      'column name' => 'column value',
     *      'column name' => 'column value',
     *  ),
     * )
     */
    public $setDbModelColumns = array();
    
    /**
     * @var boolean flag to set PHPMailer throwing external exceptions, default true.
     * @see PHPMailer::__construct()
     */
    public $setExternalExceptions = true;
    
    /**
     * METHODS =================================================================
     */
    
    /**
     * Initialise the MultiMailer object.
     */
    public function init()
    {
        parent::init();
        $this->_initState = $this->_initMethod();
    }
    
    /**
     * Initialise email sending method based on $setMethod.
     * @see $setMethod
     * Initialise the PHPMailer object with external exceptions flag.
     * @see PHPMailer::__construct()
     * Set PHPMailer options.
     * @see setPHPMailerOptions()
     */
    protected function _initMethod()
    {
        $this->_phpmailer = new PHPMailer($this->setExternalExceptions);
        $this->setPHPMailerOptions();
        
        switch (strtoupper($this->setMethod)) {
            case 'DB':
                return $this->_initDB();
            
            case 'SMTP':
                return $this->_initSMTP();
            
            default:
                return $this->_initMAIL();
        }
    }
    
    /**
     * Initialise the MAIL method.
     * Emails are sent using mail() function.
     * @see http://php.net/manual/en/function.mail.php
     * This is the default method.
     * @return boolean
     */
    protected function _initMAIL()
    {
        return true;
    }
    
    /**
     * Initialise the SMTP method.
     * Emails are sent using SMTP server.
     * This method requires $setOptions for SMTP credentials.
     * Below are the default ones:
     * array(
     *  'Host'          => 'mail.example.com',
     *  'Port'          => 25,
     *  'SMTPAuth'      => true,
     *  'Username'      => 'yourname@example.com',
     *  'Password'      => 'yourpassword',
     *  'SMTPDebug'     => 0,
     *  'Debugoutput'   => 'html',
     * )
     * @see PHPMailer::smtpConnect()
     * This method requires PHPMailer to be initialised first.
     * @return boolean
     */
    protected function _initSMTP()
    {
        if (!is_null($this->_phpmailer)) {
            $this->_phpmailer->isSMTP();
        }
        else {
            $this->setMultiError(self::ERR_PHPMAILER_NOT_SET);
            return false;
        }
        
        return true;
    }
    
    /**
     * Initialise the DB method
     * Emails are stored in the database.
     * @see $setDbModel
     * Set default model properties.
     * @see $_defaultColumns
     * @see $setDbModelColumns
     * Validate model properties.
     * This method requires $setDbModel to be set (AR model of the database table).
     * @return boolean
     */
    protected function _initDB()
    {
        if (!empty($this->setDbModel) && is_string($this->setDbModel)) {
            
            foreach ($this->_defaultColumns as $key => $value) {
                if (!isset($this->setDbModelColumns[$key])) {
                    $this->setDbModelColumns[$key] = $value;
                }
            }
            foreach ($this->setDbModelColumns as $key => $value) {
                if ($key != self::KEY_OTHERS) {
                    
                    if (!is_string($value) && !is_null($value)) {
                        
                        $this->setMultiError(self::ERR_DB_PROPERTY_TYPE);
                        return false;
                    }
                }
            }
        }
        else {
            $this->setMultiError(self::ERR_DB_MODEL_NOT_SET);
            return false;
        }
        
        return true;
    }
    
    /**
     * Get error message.
     * @return string error message
     */
    public function getMultiError()
    {
        return $this->_error;
    }
    
    /**
     * Set and log (optionally) error message.
     * @param string $error error message
     * @see Yii::log()
     */
    public function setMultiError($error)
    {
        $this->_error = $error;
        if ($this->setLogging) {
            Yii::log($error, 'error', self::LOG_CATEGORY);
        }
    }
    
    /**
     * Set default options and headers for PHPMailer.
     * @see setDefaultPHPMailerOptions()
     * Set additional options for PHPMailer.
     * You can set here any option that PHPMailer allows.
     * @see $setOptions
     * Additional option overwrites the default one of the same name.
     */
    public function setPHPMailerOptions()
    {
        $this->setDefaultPHPMailerOptions();
        
        foreach ($this->setOptions as $name => $value) {
            $this->_phpmailer->$name = $value;
        }        
    }
    
    /**
     * Default options for PHPMailer.
     * Set some headers.
     * @see from()
     * @see replyTo()
     * @see PHPMailer::isHTML()
     * @see PHPMailer::isSMTP()
     * Some of the options explained by PHPMailer:
     * SMTPDebug
     *  0 = off (for production use)
     *  1 = client messages
     *  2 = client and server messages
     * Debugoutput 'html' Ask for HTML-friendly debug output.
     * Host 'mail.example.com' Set the hostname of the mail server.
     * Port 25 Set the SMTP port number - likely to be 25, 465 or 587.
     * SMTPAuth true Whether to use SMTP authentication
     * Username 'yourname@example.com' Username to use for SMTP authentication
     * Password 'yourpassword' Password to use for SMTP authentication
     */
    public function setDefaultPHPMailerOptions()
    {
        $this->_phpmailer->CharSet      = 'UTF-8';
        $this->_phpmailer->Mailer       = 'mail';
        $this->_phpmailer->SMTPDebug    = 0;
        $this->_phpmailer->Debugoutput  = 'html';
        $this->_phpmailer->Host         = 'mail.example.com';
        $this->_phpmailer->Port         = 25;
        $this->_phpmailer->SMTPAuth     = true;
        $this->_phpmailer->Username     = 'yourname@example.com';
        $this->_phpmailer->Password     = 'yourpassword';
        
        if ($this->setContentType == 'html') {
            $this->_phpmailer->isHTML();
        }
        
        if (!empty($this->setFromAddress)) {
            $address    = $this->setFromAddress;
            $name       = !empty($this->setFromName) ? $this->setFromName : '';
            $this->from($address, $name, true, true);

            if ($this->setSameReply === true) {
                $this->replyto($address, $name, true);
            }
        }
        
        if ($this->setSameReply === false && !empty($this->setReplyAddress)) {
            $address    = $this->setReplyAddress;
            $name       = !empty($this->setReplyName) ? $this->setReplyName : '';
            $this->replyto($address, $name, true);
        }
    }
    
    /**
     * Add recipient with email address and name for initialised object.
     * @see PHPMailer::addAddress()
     * @see _to()
     * @param string $address recipient's email address
     * @param string $name optional recipient's name
     * @return \MultiMailer
     */
    public function to($address, $name = '')
    {
        if ($this->_initState) {
            $this->_to($address, $name);
        }
        
        return $this;
    }
    
    /**
     * Add recipient with email address and name.
     * @see PHPMailer::addAddress()
     * @see $_addresses
     * @param string $address recipient's email
     * @param string $name optional recipient's name
     */
    protected function _to($address, $name = '')
    {
        $this->_phpmailer->addAddress($address, $name);
        
        $address    = trim($address);
        $name       = trim(preg_replace('/[\r\n]+/', '', $name));
        if ($this->_phpmailer->validateAddress($address)) {
            $this->_addresses[] = array('email' => $address, 'name' => $name);
        }
        else {
            $this->setMultiError(self::ERR_EMAIL_INVALID);
        }
    }
    
    /**
     * Set 'from' header.
     * @see PHPMailer::setFrom()
     * @param string $address sender's email address
     * @param string $name optional sender's name
     * @param boolean $auto sets 'reply to' header automatically to the same address if true
     * @param boolean $skipInit if true skips initiation check
     * @see setDefaultPHPMailerOptions()
     * @return \MultiMailer
     */
    public function from($address, $name = '', $auto = true, $skipInit = false)
    {
        if ($skipInit || $this->_initState) {
            $this->_phpmailer->setFrom($address, $name, $auto);
        }
        
        return $this;
    }
    
    /**
     * Set 'reply to' header.
     * This is optional method to use when 'reply to' address is different than
     * 'from' address. In any other case this is set automatically.
     * @see PHPMailer::addReplyTo()
     * @param string $address email address to reply to
     * @param string $name optional name to reply to
     * @param boolean $skipInit if true skips initiation check
     * @see setDefaultPHPMailerOptions()
     * @return \MultiMailer
     */
    public function replyto($address, $name = '', $skipInit = false)
    {
        if ($skipInit || $this->_initState) {
            $this->_phpmailer->addReplyTo($address, $name);
        }
        
        return $this;
    }
    
    /**
     * Set email subject for initialised object.
     * @param string $subject email subject
     * @return \MultiMailer
     */
    public function subject($subject)
    {
        if ($this->_initState) {
            $this->_phpmailer->Subject = $subject;
        }
        
        return $this;
    }
    
    /**
     * Set email body with additional parameters for initialised object.
     * @see PHPMailer::msgHTML()
     * @param string|array $body email body
     * This is string for non-templated email or array for templated one.
     * @see _processBody()
     * @param string $basedir optional baseline directory for path
     * @param boolean $advanced whether to use the advanced HTML to text converter
     * @return \MultiMailer
     */
    public function body($body, $basedir = '', $advanced = false)
    {
        if ($this->_initState) {
            $this->_body         = $body;
            $this->_bodyBasedir  = $basedir;
            $this->_bodyAdvanced = $advanced;
        }
        
        return $this;
    }
    
    /**
     * Set alternative body (optionally) for initialised object.
     * You can skip this method for setContentType = html because AltBody is set
     * automatically when adding html body content.
     * @param string $altbody alternative plain text body
     * @return \MultiMailer
     */
    public function altbody($altbody)
    {
        if ($this->_initState) {
            $this->_phpmailer->AltBody = $altbody;
        }
        
        return $this;
    }
    
    /**
     * Process the email body with optional template.
     * @see PHPMailer::msgHTML()
     * @see $_template
     * @see $_body
     * @see Yii::renderPartial()
     * When using email template make sure to set proper view name ($_template)
     * i.e. use '//' at the beginning etc. and to set all the template variables
     * as $_body array keys with values.
     * This method automatically sets AltBody to plain text version of html text
     * body when $setContentType is 'html'.
     */
    protected function _processBody()
    {
        if (!is_null($this->_template) && is_array($this->_body)) {
            if (is_object(Yii::app()->controller)) {
                $body = Yii::app()->controller->renderPartial($this->_template, $this->_body, true);
            }
            else {
                $this->setMultiError(self::ERR_YII_CONTROLLER_NOT_SET);
            }
        }
        else {
            $body = $this->_body;
        }

        if ($this->setContentType == 'html') {
            $this->_phpmailer->msgHTML($body, $this->_bodyBasedir, $this->_bodyAdvanced);
        }
        else {
            $this->_phpmailer->Body = $body;
        }
    }
    
    /**
     * Set email template for initialised object.
     * @param string $template view name
     * @see Yii::renderPartial()
     * @return \MultiMailer
     */
    public function template($template)
    {
        if ($this->_initState) {
            $this->_template = $template;
        }
        
        return $this;
    }
    
    /**
     * Prepare email body and send (or save) email
     * @see PHPMailer::Send()
     * @return boolean whether email has been sent (or saved)
     */
    public function send()
    {
        if ($this->_initState) {
            
            try {
                
                $this->_processBody();
                
                if (strtoupper($this->setMethod) == 'DB') {
                    $this->_saveModel();
                }
                else {
                    $this->_phpmailer->Send();
                }
                
                return true;
            }
            catch(phpmailerException $e) {
                
                $this->setMultiError($e->_errorMessage());
                return false;
            }
            catch(Exception $e) {
                
                $this->setMultiError($e->getMessage());
                return false;
            }
        }
        else {
            return false;
        }
    }
    
    /**
     * Validate and save email in database using AR model for every recipient.
     * @see $setDbModelColumns
     * @see Yii::setAttribute()
     * @see Yii::save()
     * @throws Exception
     */
    protected function _saveModel()
    {
        foreach ($this->_addresses as $address) {
            
            $this->_model = new $this->setDbModel;
            
            if ($this->_model) {
                
                foreach ($this->setDbModelColumns as $key => $value) {
                    if ($key != self::KEY_OTHERS) {
                        if (!is_null($value)) {
                            switch ($key) {
                                case self::COLUMN_EMAIL:
                                    $emailProperty = $address['email'];
                                    break;
                                
                                case self::COLUMN_NAME:
                                    $emailProperty = $address['name'];
                                    break;
                                
                                case self::COLUMN_SUBJECT:
                                    $emailProperty = $this->_phpmailer->Subject;
                                    break;
                                
                                case self::COLUMN_BODY:
                                    $emailProperty = $this->_phpmailer->Body;
                                    break;
                                
                                case self::COLUMN_ALTBODY:
                                    $emailProperty = $this->_phpmailer->AltBody;
                                    break;
                            }
                            $this->_model->setAttribute($value, $emailProperty);
                        }
                    }
                    else {
                        foreach ($value as $otherKey => $otherValue) {
                            $this->_model->setAttribute($otherKey, $otherValue);
                        }
                    }
                }

                if (!($this->_model->save())) {
                    throw new Exception(self::ERR_DB_MODEL_SAVE);
                }
            }
            else {
                throw new Exception(self::ERR_DB_MODEL_INIT);
            }
        }
    }
}
